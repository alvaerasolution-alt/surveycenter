@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Customer Story</h1>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Errors -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('customer-stories.update', $customerStory->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $customerStory->title) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300" required>
        </div>

        <!-- Highlight -->
        <div>
            <label for="highlight" class="block text-sm font-medium text-gray-700 mb-1">Highlight</label>
            <input type="text" id="highlight" name="highlight" value="{{ old('highlight', $customerStory->highlight) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300" required>
        </div>

        <!-- Highlight Color -->
        <div>
            <label for="highlight_color" class="block text-sm font-medium text-gray-700 mb-1">Highlight Color (Hex)</label>
            <input type="text" id="highlight_color" name="highlight_color" value="{{ old('highlight_color', $customerStory->highlight_color) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300" placeholder="#FFD700">
        </div>

        <!-- Subheading -->
        <div>
            <label for="subheading" class="block text-sm font-medium text-gray-700 mb-1">Subheading</label>
            <input type="text" id="subheading" name="subheading" value="{{ old('subheading', $customerStory->subheading) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300" required>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description" name="description" rows="4"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300" required>{{ old('description', $customerStory->description) }}</textarea>
        </div>

        <!-- Current Image -->
        <div>
            <p class="text-sm text-gray-700 mb-2">Current Image:</p>
            <img src="{{ asset('storage/' . $customerStory->image) }}" alt="Current Image"
                class="w-48 h-32 object-cover rounded shadow mb-2">
        </div>

        <!-- New Image Upload -->
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Replace Image (optional)</label>
            <input type="file" id="image" name="image" class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300">
            <small class="text-gray-500">Allowed formats: JPG, PNG, JPEG (max 2MB)</small>
        </div>

        <!-- Button Text -->
        <div>
            <label for="button_text" class="block text-sm font-medium text-gray-700 mb-1">Button Text (optional)</label>
            <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $customerStory->button_text) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300">
        </div>

        <!-- Button Link -->
        <div>
            <label for="button_link" class="block text-sm font-medium text-gray-700 mb-1">Button Link (optional)</label>
            <input type="url" id="button_link" name="button_link" value="{{ old('button_link', $customerStory->button_link) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-orange-300">
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition">
                Update Story
            </button>
        </div>
    </form>
</div>
@endsection
