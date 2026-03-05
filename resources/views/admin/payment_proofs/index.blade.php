@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Payment Proofs</h1>
    
    <table class="min-w-full border border-gray-300 mb-6">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Transaction</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Phone</th>
                <th class="border px-4 py-2">Note</th>
                <th class="border px-4 py-2">Image</th>
                <th class="border px-4 py-2">Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proofs as $proof)
            <tr class="text-center">
                <td class="border px-4 py-2">{{ $proof->id }}</td>
                <td class="border px-4 py-2">{{ $proof->transaction->id ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $proof->name }}</td>
                <td class="border px-4 py-2">{{ $proof->phone }}</td>
                <td class="border px-4 py-2">{{ $proof->note ?? '-' }}</td>
                <td class="border px-4 py-2">
                    @if($proof->file_path)
                        <img src="{{ asset('storage/'.$proof->file_path) }}" alt="Proof" class="w-48 h-auto">
                    @else
                        -
                    @endif
                </td>
                <td class="border px-4 py-2">{{ $proof->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
