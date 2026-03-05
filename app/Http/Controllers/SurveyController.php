<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Layanan;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
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
        $request->validate([
            'title'            => 'required|string|max:255',
            'question_count'   => 'required|integer|min:1',
            'respond_count'    => 'required|integer|min:1',
            'google_form_link' => 'required|url',
        ]);

        // Simpan survey
        $survey = Survey::create([
            'title'          => $request->title,
            'question_count' => $request->question_count,
            'user_id'        => Auth::id(),
        ]);

        // Simpan response (jumlah responden + link form)
        $response = Response::create([
            'survey_id'        => $survey->id,
            'user_id'          => Auth::id(),
            'respond_count'    => $request->respond_count,
            'google_form_link' => $request->google_form_link,
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
