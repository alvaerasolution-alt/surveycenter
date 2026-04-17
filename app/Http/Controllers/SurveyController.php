<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Layanan;
use App\Models\Response;
use App\Services\FormLinkValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SurveyController extends Controller
{
    public function __construct(private FormLinkValidationService $formLinkValidationService)
    {
    }

    public function index()
    {
        $surveys = Survey::with('responses')->latest()->paginate(10);

         $jenis = Layanan::where('category', 'jenis')->get();
        $tambahan = Layanan::where('category', 'tambahan')->get();

        return view('surveys.index', compact('surveys','jenis','tambahan'));
    }

    public function create()
    {
         $jenis = Layanan::where('category', 'jenis')->get();
        $tambahan = Layanan::where('category', 'tambahan')->get();

        return view('surveys.create', compact('jenis','tambahan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'question_count'   => 'required|integer|min:1',
            'respond_count'    => 'required|integer|min:1',
            'google_form_link' => 'required|url|max:2048',
        ]);

        $formLinkError = $this->formLinkValidationService->validate(
            $validated['google_form_link'] ?? null,
            $validated['title']
        );

        if ($formLinkError !== null) {
            throw ValidationException::withMessages(['google_form_link' => $formLinkError]);
        }

        // Simpan survey
        $survey = Survey::create([
            'title'          => $validated['title'],
            'question_count' => $validated['question_count'],
            'respondent_count' => $validated['respond_count'],
            'user_id'        => Auth::id(),
        ]);

        // Simpan response (jumlah responden + link form)
        $response = Response::create([
            'survey_id'        => $survey->id,
            'user_id'          => Auth::id(),
            'respond_count'    => $validated['respond_count'],
            'google_form_link' => $validated['google_form_link'] ?? null,
        ]);

        // Arahkan langsung ke transaksi (bisa pakai survey_id atau transaction flow)
        return redirect()
            ->route('transactions.create', $survey->id)
            ->with('success', 'Survey berhasil dibuat, silakan lanjutkan ke transaksi.');
    }

    public function show(Survey $survey)
    {
        $survey->load('responses');
        return view('surveys.show', compact('survey'));
    }
}
