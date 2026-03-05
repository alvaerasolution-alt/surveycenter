@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Add New Logo</h2>
    <form action="{{ route('partner-logos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" class="border rounded w-full px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Logo</label>
            <input type="file" name="logo" class="border rounded w-full px-3 py-2">
        </div>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
    </form>
</div>
@endsection
