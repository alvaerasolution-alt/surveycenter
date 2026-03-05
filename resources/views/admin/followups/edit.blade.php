@extends('layouts.crm')

@section('title', 'Edit Follow Up')
@section('page-title', 'Edit Follow Up')

@section('content')
<div class="bg-white shadow-lg rounded-2xl p-6">
    <form action="{{ route('followups.update', $followup->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700">Customer</label>
            <select name="customer_id" class="w-full border rounded-lg p-2">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $followup->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700">Tanggal Follow-Up</label>
            <input type="datetime-local" name="follow_up_date" value="{{ $followup->follow_up_date }}" class="w-full border rounded-lg p-2" required>
        </div>

        <div>
            <label class="block text-gray-700">Status</label>
            <select name="status" class="w-full border rounded-lg p-2">
                <option value="pending" {{ $followup->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="contacted" {{ $followup->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="negotiation" {{ $followup->status == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                <option value="closed" {{ $followup->status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700">Catatan</label>
            <textarea name="note" class="w-full border rounded-lg p-2">{{ $followup->note }}</textarea>
        </div>

        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            Update
        </button>
    </form>
</div>
@endsection
