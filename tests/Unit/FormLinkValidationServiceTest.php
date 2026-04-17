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
}
