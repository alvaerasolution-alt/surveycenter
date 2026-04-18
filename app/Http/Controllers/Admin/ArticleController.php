<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Services\SitemapService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()
            ->when(request('search'), function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('excerpt', 'like', '%' . request('search') . '%')
                    ->orWhere('content', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();

        $data['is_published'] = $request->boolean('is_published');

        $data['slug'] = Str::slug($request->title);

        $originalSlug = $data['slug'];
        $counter = 1;
        while (Article::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter++;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        if ($data['is_published']) {
            $data['published_at'] = $request->filled('published_at')
                ? Carbon::parse($request->published_at)
                : now();
        } else {
            $data['published_at'] = null;
        }

        [$data['meta_title'], $data['meta_description']] = $this->generateMetaFields(
            $request->title,
            $request->content
        );

        Article::create($data);

        if ($data['is_published']) {
            $this->regenerateSitemap();
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully!');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'excerpt'  => 'nullable|string|max:500',
            'content'  => 'required|string',
            'category' => 'nullable|string|max:100',
            'image'    => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $article = Article::findOrFail($id);

        $data = $request->all();
        $data['is_published'] = $request->boolean('is_published');

        if ($article->title !== $request->title) {
            $data['slug'] = Str::slug($request->title);

            $originalSlug = $data['slug'];
            $counter = 1;
            while (Article::where('slug', $data['slug'])->where('id', '!=', $article->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter++;
            }
        }

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        if ($data['is_published']) {
            $data['published_at'] = $request->filled('published_at')
                ? Carbon::parse($request->published_at)
                : ($article->published_at ?: now());
        } else {
            $data['published_at'] = null;
        }

        if ($data['is_published']) {
            [$data['meta_title'], $data['meta_description']] = $this->generateMetaFields(
                $request->title,
                $request->content
            );
        }

        $sitemapNeedsRefresh = $article->is_published || $data['is_published'];

        $article->update($data);

        if ($sitemapNeedsRefresh) {
            $this->regenerateSitemap();
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully!');
    }


    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $wasPublished = $article->is_published;

        if ($article->image && Storage::disk('public')->exists($article->image)) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        if ($wasPublished) {
            $this->regenerateSitemap();
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully!');
    }

    public function togglePublish($id)
    {
        $article = Article::findOrFail($id);
        $article->is_published = ! $article->is_published;

        if ($article->is_published) {
            $article->published_at = $article->published_at ?: now();
            [$article->meta_title, $article->meta_description] = $this->generateMetaFields(
                $article->title,
                $article->content
            );
        } else {
            $article->published_at = null;
        }

        $article->save();
        $this->regenerateSitemap();

        $status = $article->is_published ? 'published' : 'draft';

        return redirect()->route('admin.articles.index')->with('success', "Article berhasil diubah ke {$status}.");
    }

    private function generateMetaFields(string $title, string $content): array
    {
        $metaTitle = Str::limit(trim(strip_tags($title)), 60, '');
        $plainContent = trim(preg_replace('/\s+/', ' ', strip_tags($content)) ?? '');
        $metaDescription = Str::limit($plainContent, 160, '');

        return [$metaTitle, $metaDescription];
    }

    private function regenerateSitemap(): void
    {
        try {
            app(SitemapService::class)->generate();
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
