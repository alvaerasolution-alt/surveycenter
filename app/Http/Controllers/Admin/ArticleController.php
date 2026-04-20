<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Article;
use App\Services\SitemapService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

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
            $data['image'] = $this->storeArticleImage($request->file('image'));
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
            $data['image'] = $this->storeArticleImage($request->file('image'));
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

    public function bulkPublish(Request $request)
    {
        $data = $request->validate([
            'article_ids' => ['required', 'array', 'min:1'],
            'article_ids.*' => ['required', 'integer', 'exists:articles,id'],
        ]);

        $articleIds = array_values(array_unique($data['article_ids']));
        $articles = Article::whereIn('id', $articleIds)->get();

        $publishedCount = 0;

        foreach ($articles as $article) {
            $shouldSave = false;

            if (!$article->is_published) {
                $article->is_published = true;
                $shouldSave = true;
            }

            if (!$article->published_at) {
                $article->published_at = now();
                $shouldSave = true;
            }

            if (!$article->meta_title || !$article->meta_description) {
                [$metaTitle, $metaDescription] = $this->generateMetaFields($article->title, $article->content);
                $article->meta_title = $article->meta_title ?: $metaTitle;
                $article->meta_description = $article->meta_description ?: $metaDescription;
                $shouldSave = true;
            }

            if ($shouldSave) {
                $article->save();
                $publishedCount++;
            }
        }

        if ($publishedCount > 0) {
            $this->regenerateSitemap();
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', "{$publishedCount} artikel berhasil dipublish.");
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
            Cache::forget('home_articles');
            Cache::increment('blog_cache_version');

            app(SitemapService::class)->generate();
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function storeArticleImage(UploadedFile $file): string
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagecreatetruecolor')) {
            return $file->store('articles', 'public');
        }

        try {
            $rawContent = file_get_contents($file->getRealPath());
            $sourceImage = $rawContent ? \imagecreatefromstring($rawContent) : false;

            if (! $sourceImage) {
                return $file->store('articles', 'public');
            }

            $sourceWidth = \imagesx($sourceImage);
            $sourceHeight = \imagesy($sourceImage);

            // Max width 1200px
            $targetWidth = min($sourceWidth, 1200);
            $ratio = $targetWidth / $sourceWidth;
            $targetHeight = (int) round($sourceHeight * $ratio);

            $targetImage = \imagecreatetruecolor($targetWidth, $targetHeight);

            // Handle transparency for PNG/WebP if needed (converting to white background for JPEG)
            $white = \imagecolorallocate($targetImage, 255, 255, 255);
            \imagefill($targetImage, 0, 0, $white);

            \imagecopyresampled(
                $targetImage,
                $sourceImage,
                0,
                0,
                0,
                0,
                $targetWidth,
                $targetHeight,
                $sourceWidth,
                $sourceHeight
            );

            ob_start();
            \imagejpeg($targetImage, null, 80);
            $processedImage = ob_get_clean();

            \imagedestroy($sourceImage);
            \imagedestroy($targetImage);

            if ($processedImage === false) {
                return $file->store('articles', 'public');
            }

            $path = 'articles/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($path, $processedImage);

            return $path;
        } catch (\Throwable $e) {
            report($e);
            return $file->store('articles', 'public');
        }
    }
}
