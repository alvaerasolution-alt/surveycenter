<?php

namespace App\Http\Controllers;

use App\Services\FormLinkValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormAnalyzerController extends Controller
{
    public function __construct(private FormLinkValidationService $formLinkValidationService)
    {
    }

    public function preview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1',
            'form_link' => 'required|url|max:2048',
        ]);

        $url = $validated['form_link'];

        if (!$this->formLinkValidationService->isAllowedFormUrl($url)) {
            return response()->json([
                'ok' => false,
                'message' => 'Domain link form tidak didukung.',
            ], 422);
        }

        $metadata = $this->formLinkValidationService->fetchFormMetadata($url);
        $fetchedTitle = $metadata['title'];
        $detectedQuestionCount = $metadata['question_count'];
        $detectedQuestions = $metadata['question_titles'] ?? [];
        $detectedQuestionItems = $metadata['question_items'] ?? [];
        $debug = $metadata['debug'] ?? [
            'entry_ids' => [],
            'entry_ids_count' => 0,
            'has_load_data' => false,
            'question_titles_count' => 0,
            'type_counts' => [],
        ];

        if ($fetchedTitle === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Judul form tidak dapat diambil dari link.',
            ], 422);
        }

        $similarityPercent = $this->formLinkValidationService->titleSimilarityPercent(
            $validated['title'],
            $fetchedTitle
        );

        $titleMatch = $similarityPercent >= FormLinkValidationService::TITLE_MATCH_THRESHOLD;

        $questionMatch = $detectedQuestionCount !== null
            ? (int) $detectedQuestionCount === (int) $validated['question_count']
            : false;

        $accepted = $titleMatch && $questionMatch;

        $questionReview = $detectedQuestionCount === null
            ? 'Jumlah pertanyaan tidak bisa dideteksi. Pastikan link form publik dan bisa diakses.'
            : ($questionMatch
                ? 'Jumlah pertanyaan cocok dengan input user.'
                : 'Jumlah pertanyaan tidak cocok dengan input user.');

        $reviewNotes = [];
        $reviewNotes[] = $titleMatch
            ? 'Judul form cocok dengan judul input.'
            : 'Judul form belum cocok dengan judul input.';
        $reviewNotes[] = $questionReview;

        return response()->json([
            'ok' => true,
            'accepted' => $accepted,
            'title' => [
                'input' => $validated['title'],
                'detected' => $fetchedTitle,
                'similarity_percent' => $similarityPercent,
                'threshold_percent' => FormLinkValidationService::TITLE_MATCH_THRESHOLD,
                'is_match' => $titleMatch,
            ],
            'question_count' => [
                'input' => (int) $validated['question_count'],
                'detected' => $detectedQuestionCount,
                'is_match' => $questionMatch,
            ],
            'detected_questions' => $detectedQuestions,
            'detected_question_items' => $detectedQuestionItems,
            'debug' => $debug,
            'question_review' => $questionReview,
            'review_notes' => $reviewNotes,
        ]);
    }
}
