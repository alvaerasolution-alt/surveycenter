<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Survey;
use App\Models\User;
use App\Services\FormLinkValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ResponseController extends Controller
{
    public function __construct(private FormLinkValidationService $formLinkValidationService)
    {
    }

    public function index()
    {
        $responses = Response::with(['survey', 'user'])->latest()->paginate(10);
        return view('admin.responses.index', compact('responses'));
    }

    public function create()
    {
        $surveys = Survey::all();
        $users = User::all();
        return view('admin.responses.create', compact('surveys', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'user_id' => 'required|exists:users,id',
            'respond_count' => 'required|integer|min:0',
            'google_form_link' => 'required|url|max:2048',
        ]);

        $survey = Survey::find($validated['survey_id']);
        $formLinkError = $this->formLinkValidationService->validate(
            $validated['google_form_link'] ?? null,
            $survey?->title
        );

        if ($formLinkError !== null) {
            throw ValidationException::withMessages(['google_form_link' => $formLinkError]);
        }

        Response::create([
            'survey_id' => $validated['survey_id'],
            'user_id' => $validated['user_id'],
            'respond_count' => $validated['respond_count'],
            'google_form_link' => $validated['google_form_link'] ?? null,
            'input_by_admin_id' => Auth::id(),
        ]);

        return redirect()->route('admin.responses.index')->with('success', 'Response berhasil ditambahkan.');
    }

    public function show(Response $response)
    {
        $response->load(['survey', 'user']);
        return view('admin.responses.show', compact('response'));
    }

    public function edit(Response $response)
    {
        $surveys = Survey::all();
        $users = User::all();
        return view('admin.responses.edit', compact('response', 'surveys', 'users'));
    }

    public function update(Request $request, Response $response)
    {
        $validated = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'user_id' => 'required|exists:users,id',
            'respond_count' => 'required|integer|min:0',
            'google_form_link' => 'required|url|max:2048',
        ]);

        $survey = Survey::find($validated['survey_id']);
        $formLinkError = $this->formLinkValidationService->validate(
            $validated['google_form_link'] ?? null,
            $survey?->title
        );

        if ($formLinkError !== null) {
            throw ValidationException::withMessages(['google_form_link' => $formLinkError]);
        }

        $response->update([
            'survey_id' => $validated['survey_id'],
            'user_id' => $validated['user_id'],
            'respond_count' => $validated['respond_count'],
            'google_form_link' => $validated['google_form_link'] ?? null,
            'input_by_admin_id' => Auth::id(),
        ]);

        return redirect()->route('admin.responses.index')->with('success', 'Response berhasil diperbarui.');
    }

    public function destroy(Response $response)
    {
        $response->delete();
        return redirect()->route('admin.responses.index')->with('success', 'Response berhasil dihapus.');
    }
}
