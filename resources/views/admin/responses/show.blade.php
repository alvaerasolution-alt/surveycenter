@extends('layouts.crm')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-3xl mx-auto bg-white shadow-2xl rounded-2xl p-8 border-l-8 border-orange-500">
            {{-- Header --}}
            <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Detail Response #{{ $response->id }}</h2>

            {{-- Info Card --}}
            <div class="space-y-4 text-gray-700">

                <div class="flex justify-between items-center bg-orange-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Survey:</span>
                    <span class="text-orange-700">{{ $response->survey->title ?? '-' }}</span>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Survey ID:</span>
                    <span>{{ $response->survey->id ?? '-' }}</span>
                </div>

                <div class="flex justify-between items-center bg-orange-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Question Count:</span>
                    <span>{{ $response->survey->question_count ?? 0 }}</span>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">User:</span>
                    <span>{{ $response->user->name ?? '-' }}</span>
                </div>

                <div class="flex justify-between items-center bg-orange-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Email:</span>
                    <span>{{ $response->user->email ?? '-' }}</span>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Respond Count:</span>
                    <span>{{ $response->respond_count }}</span>
                </div>

                <div class="flex justify-between items-center bg-orange-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Google Form:</span>
                    <span>
                        @if ($response->google_form_link)
                            <a href="{{ $response->google_form_link }}" target="_blank"
                                class="text-blue-500 underline">Lihat Link</a>
                        @else
                            -
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Created At:</span>
                    <span>{{ $response->created_at->format('d M Y H:i') }}</span>
                </div>

                <div class="flex justify-between items-center bg-orange-50 p-3 rounded shadow-inner">
                    <span class="font-semibold">Updated At:</span>
                    <span>{{ $response->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.responses.index') }}"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">Kembali</a>
                <a href="{{ route('admin.responses.edit', $response) }}"
                    class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">Edit</a>
            </div>
        </div>
    </div>
@endsection
