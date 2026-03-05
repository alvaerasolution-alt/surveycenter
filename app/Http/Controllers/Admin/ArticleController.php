<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
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
        ]);

        $data = $request->all();

        // ✅ Buat slug otomatis dari judul
        $data['slug'] = Str::slug($request->title);

        // ✅ Pastikan slug unik
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Article::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter++;
        }

        // ✅ Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('articles.index')->with('success', 'Article created successfully!');
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
        ]);

        $article = Article::findOrFail($id);

        $data = $request->all();

        // ✅ Perbarui slug jika judul berubah
        if ($article->title !== $request->title) {
            $data['slug'] = Str::slug($request->title);

            $originalSlug = $data['slug'];
            $counter = 1;
            while (Article::where('slug', $data['slug'])->where('id', '!=', $article->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter++;
            }
        }

        // ✅ Update gambar jika ada upload baru
        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
        // dd($data);
        $article->update($data);

        return redirect()->route('articles.index')->with('success', 'Article updated successfully!');
    }


    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->image && Storage::disk('public')->exists($article->image)) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully!');
    }
}
