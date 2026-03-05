@extends('layouts.crm')

@section('title', 'Tambah Follow Up')
@section('page-title', 'Tambah Follow Up')

@section('content')
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <form action="{{ route('followups.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700">Customer</label>
                <select name="customer_id" class="w-full border rounded-lg p-2">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Tanggal Follow-Up</label>
                <input type="datetime-local" name="follow_up_date" class="w-full border rounded-lg p-2" required>
            </div>

            <div>
                <label class="block text-gray-700">Status</label>
                <select name="status" class="w-full border rounded-lg p-2">
                    <option value="pending">Pending</option>
                    <option value="contacted">Contacted</option>
                    <option value="negotiation">Negotiation</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Catatan</label>
                <textarea name="note" class="w-full border rounded-lg p-2"></textarea>
            </div>

            <button class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                Simpan
            </button>
        </form>
    </div>
@endsection
