<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Article;

class ImportWordpress extends Command
{
    protected $signature = 'import:wordpress';
    protected $description = 'Import posts from WordPress into Laravel articles table';

    public function handle()
    {
        $posts = DB::table('wpgq_posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->get();

        $count = 0;

        foreach ($posts as $post) {
            // --- Ambil kategori utama ---
            $category = DB::table('wpgq_term_relationships')
                ->join('wpgq_term_taxonomy', 'wpgq_term_relationships.term_taxonomy_id', '=', 'wpgq_term_taxonomy.term_taxonomy_id')
                ->join('wpgq_terms', 'wpgq_term_taxonomy.term_id', '=', 'wpgq_terms.term_id')
                ->where('wpgq_term_relationships.object_id', $post->ID)
                ->where('wpgq_term_taxonomy.taxonomy', 'category')
                ->value('wpgq_terms.name');

            // --- Ambil thumbnail ---
            $thumbnail_id = DB::table('wpgq_postmeta')
                ->where('post_id', $post->ID)
                ->where('meta_key', '_thumbnail_id')
                ->value('meta_value');

            $image_url = null;
            if ($thumbnail_id) {
                $image_url = DB::table('wpgq_posts')
                    ->where('ID', $thumbnail_id)
                    ->value('guid');
            }

            // --- Generate slug ---
            $slug = $post->post_name ?: Str::slug($post->post_title);

            if (!$slug) {
                $slug = 'post-' . $post->ID;
            }

            // --- Simpan ke tabel articles ---
            Article::updateOrCreate(
                ['slug' => Str::slug($post->post_title)], // identifikasi berdasarkan slug
                [
                    'title'      => $post->post_title,
                    'slug'       => Str::slug($post->post_title), // WAJIB diset
                    'is_published' => true,
                    'published_at' => $post->post_date,
                    'excerpt'    => $post->post_excerpt,
                    'content'    => $post->post_content,
                    'category'   => $category,
                    'image'      => $image_url,
                    'meta_title' => Str::limit(trim(strip_tags($post->post_title)), 60, ''),
                    'meta_description' => Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $post->post_content))), 160, ''),
                    'created_at' => $post->post_date,
                    'updated_at' => $post->post_modified,
                ]
            );

            $count++;
        }

        $this->info("✅ Import selesai! Total artikel diproses: " . $count);
    }
}
