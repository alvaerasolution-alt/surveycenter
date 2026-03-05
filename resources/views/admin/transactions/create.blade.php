@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Add Transaction</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-4">
        @csrf
        @include('admin.transactions.partials.form', ['transaction' => null])
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        <a href="{{ route('admin.transactions.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
