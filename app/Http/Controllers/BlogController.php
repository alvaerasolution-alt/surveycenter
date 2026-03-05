<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $articles = $query->latest()->paginate(10);
        $recent = Article::latest()->take(5)->get();
        $categories = Article::select('category')->distinct()->get();

        return view('blog.index', compact('articles', 'recent', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $recent = Article::latest()->take(5)->get();
        $categories = Article::select('category')->distinct()->get();

        return view('show', compact('article', 'recent', 'categories'));
    }

    public function category($category)
    {
        $articles = Article::where('category', $category)->paginate(10);
        $recent = Article::latest()->take(5)->get();
        $categories = Article::select('category')->distinct()->get();

        return view('blog.index', compact('articles', 'recent', 'categories'))
            ->with('selectedCategory', $category);
    }
	
	public function getBlogs(Request $request)
    {
        $articles = Article::latest()
            ->when(request('search'), function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('excerpt', 'like', '%' . request('search') . '%')
                    ->orWhere('content', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return response()->json($articles, 200);
    }
}
