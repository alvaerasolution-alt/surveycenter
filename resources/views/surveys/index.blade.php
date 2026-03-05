@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Survey</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('surveys.create') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
       + Tambah Survey
    </a>

    <table class="w-full border-collapse border border-gray-300 mt-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">#</th>
                <th class="border border-gray-300 px-4 py-2">Judul</th>
                <th class="border border-gray-300 px-4 py-2">Jumlah Pertanyaan</th>
                <th class="border border-gray-300 px-4 py-2">Tanggal Dibuat</th>
                <th class="border border-gray-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($surveys as $survey)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $survey->title }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $survey->question_count }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $survey->created_at->format('d M Y') }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('surveys.show', $survey->id) }}" 
                           class="bg-green-500 text-white px-3 py-1 rounded">Lihat</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">
                        Tidak ada survey tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $surveys->links() }}
    </div>
</div>
@endsection
