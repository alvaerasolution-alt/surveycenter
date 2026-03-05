<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
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
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'user_id' => 'required|exists:users,id',
            'respond_count' => 'required|integer|min:0',
            'google_form_link' => 'nullable|url',
        ]);

        Response::create($request->all());

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
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'user_id' => 'required|exists:users,id',
            'respond_count' => 'required|integer|min:0',
            'google_form_link' => 'nullable|url',
        ]);

        $response->update($request->all());

        return redirect()->route('admin.responses.index')->with('success', 'Response berhasil diperbarui.');
    }

    public function destroy(Response $response)
    {
        $response->delete();
        return redirect()->route('admin.responses.index')->with('success', 'Response berhasil dihapus.');
    }
}
