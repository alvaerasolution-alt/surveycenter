@extends('layouts.crm')

@section('title', 'Responses')
@section('page-title', 'Respons')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Responses</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola respons survey dan kuesioner</p>
            </div>
            <a href="{{ route('admin.responses.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Response
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Survey</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Questions</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Responds</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Google Form</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($responses as $response)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3.5 font-medium text-gray-900">{{ $response->survey->title ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-gray-700">{{ $response->user->name ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-gray-600 text-xs">{{ $response->user->email ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $response->survey->question_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-700">
                                        {{ $response->respond_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($response->google_form_link)
                                        <a href="{{ $response->google_form_link }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-orange-600 hover:text-orange-700 text-xs font-medium">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                            Link
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-gray-500 text-xs">
                                    {{ $response->created_at ? $response->created_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.responses.show', $response) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition" title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.responses.edit', $response) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-600 transition" title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.responses.destroy', $response) }}" method="POST" class="inline" onsubmit="return confirm('Hapus response ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition" title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <i data-lucide="bar-chart-3" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                                    <p class="text-sm text-gray-500">Belum ada data response</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-2">
            {{ $responses->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
