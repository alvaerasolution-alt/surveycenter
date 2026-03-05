@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 md:px-20">
    <h1 class="text-3xl font-bold mb-4">{{ $article->title }}</h1>
    <span class="text-xs uppercase text-gray-500">{{ $article->category ?? 'Uncategorized' }}</span>
    @if($article->image)
        <img src="{{ url($article->image) }}" alt="{{ $article->title }}" class="w-full h-64 object-cover my-4 rounded">
    @endif
    <p class="text-gray-700 mt-4">{{ $article->content }}</p>
</div>
@endsection
