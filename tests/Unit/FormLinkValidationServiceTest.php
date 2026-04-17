<?php

namespace Tests\Unit;

use App\Services\FormLinkValidationService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FormLinkValidationServiceTest extends TestCase
{
    public function test_accepts_supported_google_form_url_and_matching_title(): void
    {
        Http::fake([
            'https://docs.google.com/forms/d/e/test/viewform' => Http::response(
                '<html><head><meta property="og:title" content="Survey Kepuasan Pelanggan - Google Forms"></head></html>',
                200
            ),
        ]);

        $service = app(FormLinkValidationService::class);

        $error = $service->validate(
            'https://docs.google.com/forms/d/e/test/viewform',
            'Survey Kepuasan Pelanggan'
        );

        $this->assertNull($error);
    }

    public function test_rejects_unsupported_form_domain(): void
    {
        $service = app(FormLinkValidationService::class);

        $error = $service->validate('https://example.com/form/abc', 'Judul Form');

        $this->assertSame(
            'Domain link form tidak didukung. Gunakan link Google Form atau provider form yang didukung.',
            $error
        );
    }

    public function test_rejects_when_form_title_is_different_from_input_title(): void
    {
        Http::fake([
            'https://forms.gle/test-link' => Http::response(
                '<html><head><meta property="og:title" content="Form A"></head></html>',
                200
            ),
        ]);

        $service = app(FormLinkValidationService::class);

        $error = $service->validate('https://forms.gle/test-link', 'Form B');

        $this->assertSame('Judul form pada link tidak sama dengan judul yang diinput.', $error);
    }

    public function test_rejects_when_title_cannot_be_fetched(): void
    {
        Http::fake([
            'https://forms.gle/unreachable' => Http::response('', 500),
        ]);

        $service = app(FormLinkValidationService::class);

        $error = $service->validate('https://forms.gle/unreachable', 'Form Apa Saja');

        $this->assertSame(
            'Judul form tidak dapat diambil dari link. Pastikan link form publik dan bisa diakses.',
            $error
        );
    }

    public function test_skips_title_check_when_expected_title_is_empty(): void
    {
        $service = app(FormLinkValidationService::class);

        $error = $service->validate('https://forms.gle/anything', null);

        $this->assertNull($error);
    }

    public function test_can_calculate_title_similarity_percentage(): void
    {
        $service = app(FormLinkValidationService::class);

        $percent = $service->titleSimilarityPercent('Survey Kepuasan Pelanggan', 'Survey Kepuasan Pelanggan 2026');

        $this->assertGreaterThan(80, $percent);
    }

    public function test_can_extract_question_count_from_form_html_entries(): void
    {
        Http::fake([
            'https://docs.google.com/forms/d/e/test-count/viewform' => Http::response(
                '<input name="entry.111111" /><input name="entry.222222" /><input name="entry.222222" />',
                200
            ),
        ]);

        $service = app(FormLinkValidationService::class);
        $metadata = $service->fetchFormMetadata('https://docs.google.com/forms/d/e/test-count/viewform');

        $this->assertSame(2, $metadata['question_count']);
    }

    public function test_can_extract_question_count_from_unicode_entry_pattern(): void
    {
        Http::fake([
            'https://docs.google.com/forms/d/e/test-unicode/viewform' => Http::response(
                '"entry\\u002e333333" "entry\\u002e444444" "entry\\u002e333333"',
                200
            ),
        ]);

        $service = app(FormLinkValidationService::class);
        $metadata = $service->fetchFormMetadata('https://docs.google.com/forms/d/e/test-unicode/viewform');

        $this->assertSame(2, $metadata['question_count']);
    }

    public function test_can_extract_question_count_from_google_public_load_data(): void
    {
        $payload = '[null,[null,null,null,null,null,null,[[12345,"Pertanyaan Satu",null,0,[null,null,null,["entry.111111"]]], [67890,"Pertanyaan Dua",null,0,[null,null,null,["entry.222222"]]], [99999,"Judul Seksi",null,0,[null]]]]]';

        Http::fake([
            'https://docs.google.com/forms/d/e/test-load-data/viewform' => Http::response(
                "<input name=\"entry.111111\" /><input name=\"entry.222222\" /><script>var FB_PUBLIC_LOAD_DATA_ = {$payload};</script>",
                200
            ),
        ]);

        $service = app(FormLinkValidationService::class);
        $metadata = $service->fetchFormMetadata('https://docs.google.com/forms/d/e/test-load-data/viewform');

        $this->assertSame(2, $metadata['question_count']);
        $this->assertSame(['Pertanyaan Satu', 'Pertanyaan Dua'], $metadata['question_titles']);
        $this->assertSame('short_text', $metadata['question_items'][0]['type']);
        $this->assertSame(2, $metadata['debug']['entry_ids_count']);
        $this->assertTrue($metadata['debug']['has_load_data']);
        $this->assertSame(['short_text' => 2], $metadata['debug']['type_counts']);
    }

    public function test_only_returns_titles_for_detected_entry_ids(): void
    {
        $payload = '[null,[null,null,null,null,null,null,[[111,"Pertanyaan A",null,0,[null,null,null,["entry.111111"]]], [222,"Pertanyaan B",null,0,[null,null,null,["entry.222222"]]], [333,"Pertanyaan C",null,0,[null,null,null,["entry.333333"]]]]]]';

        Http::fake([
            'https://docs.google.com/forms/d/e/test-filtered-titles/viewform' => Http::response(
                "<input name=\"entry.111111\" /><input name=\"entry.333333\" /><script>var FB_PUBLIC_LOAD_DATA_ = {$payload};</script>",
                200
            ),
        ]);

        $service = app(FormLinkValidationService::class);
        $metadata = $service->fetchFormMetadata('https://docs.google.com/forms/d/e/test-filtered-titles/viewform');

        $this->assertSame(2, $metadata['question_count']);
        $this->assertSame(['Pertanyaan A', 'Pertanyaan C'], $metadata['question_titles']);
    }

    public function test_fallback_detects_numbered_questions_when_entry_ids_are_missing(): void
    {
        $payload = '[null,[null,null,null,null,null,null,[[111,"1. Pertanyaan Satu",null,0,[null]], [222,"2. Pertanyaan Dua",null,0,[null]], [333,"Bagian I",null,0,[null]]]]]';

        Http::fake([
            'https://docs.google.com/forms/d/e/test-fallback/viewform' => Http::response(
                "<script>var FB_PUBLIC_LOAD_DATA_ = {$payload};</script>",
                200
            ),
        ]);

        $service = app(FormLinkValidationService::class);
        $metadata = $service->fetchFormMetadata('https://docs.google.com/forms/d/e/test-fallback/viewform');

        $this->assertSame(2, $metadata['question_count']);
        $this->assertSame(['1. Pertanyaan Satu', '2. Pertanyaan Dua'], $metadata['question_titles']);
        $this->assertSame(2, count($metadata['question_items']));
    }
}
