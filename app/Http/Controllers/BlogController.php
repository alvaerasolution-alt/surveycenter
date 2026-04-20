<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $page = max(1, (int) $request->query('page', 1));
        $version = $this->getBlogCacheVersion();
        $cacheKey = sprintf('blog_v%s:index:%s:%s', $version, md5($search), $page);

        $articles = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($search) {
            $query = Article::query()->published();

            $this->applySearchFilter($query, $search);

            return $query
                ->select(['id', 'title', 'slug', 'excerpt', 'content', 'category', 'image', 'created_at'])
                ->latest()
                ->paginate(10)
                ->withQueryString();
        });

        $recent = $this->getRecentArticles();
        $categories = $this->getCategories();

        return view('blog.index', compact('articles', 'recent', 'categories'));
    }

    public function show($slug)
    {
        $version = $this->getBlogCacheVersion();
        $article = Cache::remember("blog_v{$version}:article:{$slug}", now()->addMinutes(10), function () use ($slug) {
            return Article::published()->where('slug', $slug)->first();
        });

        if (!$article) {
            abort(404);
        }

        $recent = $this->getRecentArticles();
        $categories = $this->getCategories();
        $relatedArticles = $this->getRelatedArticles($article->id, $article->category);

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
        $page = max(1, (int) request('page', 1));
        $version = $this->getBlogCacheVersion();
        $cacheKey = sprintf('blog_v%s:category:%s:%s', $version, md5((string) $category), $page);

        $articles = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($category) {
            return Article::published()
                ->where('category', $category)
                ->select(['id', 'title', 'slug', 'excerpt', 'content', 'category', 'image', 'created_at'])
                ->latest()
                ->paginate(10)
                ->withQueryString();
        });

        $recent = $this->getRecentArticles();
        $categories = $this->getCategories();

        return view('blog.index', compact('articles', 'recent', 'categories'))
            ->with('selectedCategory', $category);
    }
	
	public function getBlogs(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $page = max(1, (int) $request->query('page', 1));
        $version = $this->getBlogCacheVersion();
        $cacheKey = sprintf('blog_v%s:api:%s:%s', $version, md5($search), $page);

        $articles = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($search) {
            $query = Article::query()->published();
            $this->applySearchFilter($query, $search);

            return $query
                ->select(['id', 'title', 'slug', 'excerpt', 'content', 'category', 'image', 'created_at'])
                ->latest()
                ->paginate(10);
        });

        return response()->json($articles, 200);
    }

    private function getRecentArticles()
    {
        $version = $this->getBlogCacheVersion();

        return Cache::remember("blog_v{$version}:recent", now()->addMinutes(10), function () {
            return Article::published()
                ->select(['id', 'title', 'slug', 'category', 'created_at'])
                ->latest()
                ->take(5)
                ->get();
        });
    }

    private function getCategories()
    {
        $version = $this->getBlogCacheVersion();

        return Cache::remember("blog_v{$version}:categories", now()->addMinutes(30), function () {
            return Article::published()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->get();
        });
    }

    private function getRelatedArticles(int $articleId, ?string $category)
    {
        $version = $this->getBlogCacheVersion();
        $categoryKey = $category ?? '-';
        $cacheKey = sprintf('blog_v%s:related:%s:%s', $version, $articleId, md5($categoryKey));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($articleId, $category) {
            $relatedArticles = Article::published()
                ->where('id', '!=', $articleId)
                ->when($category, function ($query) use ($category) {
                    $query->where('category', $category);
                })
                ->select(['id', 'title', 'slug'])
                ->latest()
                ->take(4)
                ->get();

            if ($relatedArticles->isNotEmpty()) {
                return $relatedArticles;
            }

            return Article::published()
                ->where('id', '!=', $articleId)
                ->select(['id', 'title', 'slug'])
                ->latest()
                ->take(4)
                ->get();
        });
    }

    private function getBlogCacheVersion(): int
    {
        return (int) Cache::get('blog_cache_version', 1);
    }

    private function applySearchFilter($query, string $search): void
    {
        if ($search === '') {
            return;
        }

        if ($this->supportsFullText()) {
            $query->whereFullText(['title', 'excerpt', 'content'], $search);
            return;
        }

        $query->where(function ($searchQuery) use ($search) {
            $searchQuery->where('title', 'like', '%' . $search . '%')
                ->orWhere('excerpt', 'like', '%' . $search . '%')
                ->orWhere('content', 'like', '%' . $search . '%');
        });
    }

    private function supportsFullText(): bool
    {
        return in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb', 'pgsql'], true);
    }
}
