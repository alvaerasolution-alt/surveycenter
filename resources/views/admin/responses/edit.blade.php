@extends('layouts.crm')

@section('content')
<div class="bg-white shadow-lg rounded-2xl p-6 max-w-3xl mx-auto mt-10">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Response #{{ $response->id }}</h2>
        <a href="{{ route('admin.responses.index') }}" 
           class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">Kembali</a>
    </div>

    {{-- Notifikasi --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.responses.update', $response) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Survey --}}
        <div>
            <label for="survey_id" class="block font-semibold mb-1 text-gray-700">Survey</label>
            <select name="survey_id" id="survey_id" class="w-full border rounded p-2 focus:ring-2 focus:ring-orange-400">
                @foreach($surveys as $survey)
                    <option value="{{ $survey->id }}" {{ $response->survey_id == $survey->id ? 'selected' : '' }}>
                        {{ $survey->title }} ({{ $survey->question_count }} questions)
                    </option>
                @endforeach
            </select>
        </div>

        {{-- User --}}
        <div>
            <label for="user_id" class="block font-semibold mb-1 text-gray-700">User</label>
            <select name="user_id" id="user_id" class="w-full border rounded p-2 focus:ring-2 focus:ring-orange-400">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $response->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Respond Count --}}
        <div>
            <label for="respond_count" class="block font-semibold mb-1 text-gray-700">Respond Count</label>
            <input type="number" name="respond_count" id="respond_count" 
                   value="{{ $response->respond_count }}" 
                   class="w-full border rounded p-2 focus:ring-2 focus:ring-orange-400" min="0">
        </div>

        {{-- Google Form Link --}}
        <div>
            <label for="google_form_link" class="block font-semibold mb-1 text-gray-700">Google Form Link</label>
            <input type="url" name="google_form_link" id="google_form_link" 
                   value="{{ $response->google_form_link }}" 
                   class="w-full border rounded p-2 focus:ring-2 focus:ring-orange-400" placeholder="https://...">
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-3 mt-4">
            <a href="{{ route('admin.responses.index') }}" 
               class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">Batal</a>
            <button type="submit" 
               class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">Simpan Perubahan</button>
        </div>

    </form>
</div>
@endsection
