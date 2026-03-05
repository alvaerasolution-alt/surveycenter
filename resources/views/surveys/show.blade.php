@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $survey->title }}</h1>

    <p class="mb-2 text-gray-700">
        <strong>Jumlah Pertanyaan:</strong> {{ $survey->question_count }}
    </p>

    <p class="mb-2 text-gray-700">
        <strong>Tanggal Dibuat:</strong> {{ $survey->created_at->format('d M Y H:i') }}
    </p>

    <a href="{{ route('surveys.index') }}" 
       class="bg-gray-500 text-white px-4 py-2 rounded mt-4 inline-block">
       Kembali
    </a>
</div>
@endsection
