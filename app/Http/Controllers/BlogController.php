<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query()->published();

        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $articles = $query->latest()->paginate(10);
        $recent = Article::published()->latest()->take(5)->get();
        $categories = Article::published()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get();

        return view('blog.index', compact('articles', 'recent', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();
        $recent = Article::published()->latest()->take(5)->get();
        $categories = Article::published()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get();

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->when($article->category, function ($query) use ($article) {
                $query->where('category', $article->category);
            })
            ->latest()
            ->take(4)
            ->get();

        if ($relatedArticles->isEmpty()) {
            $relatedArticles = Article::published()
                ->where('id', '!=', $article->id)
                ->latest()
                ->take(4)
                ->get();
        }

        $seoTitle = $article->meta_title ?: $article->title;
        $seoDesc = $article->meta_description
            ?: \Illuminate\Support\Str::limit(
                trim(preg_replace('/\s+/', ' ', strip_tags($article->excerpt ?: $article->content)) ?? ''),
                160,
                ''
            );

        return view('show', compact('article', 'recent', 'categories', 'relatedArticles', 'seoTitle', 'seoDesc'));
    }

    public function category($category)
    {
        $articles = Article::published()->where('category', $category)->paginate(10);
        $recent = Article::published()->latest()->take(5)->get();
        $categories = Article::published()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get();

        return view('blog.index', compact('articles', 'recent', 'categories'))
            ->with('selectedCategory', $category);
    }
	
	public function getBlogs(Request $request)
    {
        $articles = Article::published()->latest()
            ->when(request('search'), function ($query) {
                $query->where(function ($searchQuery) {
                    $searchQuery->where('title', 'like', '%' . request('search') . '%')
                        ->orWhere('excerpt', 'like', '%' . request('search') . '%')
                        ->orWhere('content', 'like', '%' . request('search') . '%');
                });
            })
            ->paginate(10);

        return response()->json($articles, 200);
    }
}
