@extends('layouts.crm')

@section('content')
<div class="bg-white shadow-lg rounded-2xl p-6 max-w-3xl mx-auto mt-10">
    <h2 class="text-xl font-bold mb-6">Tambah Response</h2>

    <form action="{{ route('admin.responses.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Survey</label>
            <select name="survey_id" class="w-full border p-2 rounded">
                @foreach($surveys as $survey)
                    <option value="{{ $survey->id }}">{{ $survey->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">User</label>
            <select name="user_id" class="w-full border p-2 rounded">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Respond Count</label>
            <input type="number" name="respond_count" class="w-full border p-2 rounded" min="0">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Google Form Link (Opsional)</label>
            <input type="url" name="google_form_link" class="w-full border p-2 rounded">
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.responses.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Simpan</button>
        </div>
    </form>
</div>
@endsection
