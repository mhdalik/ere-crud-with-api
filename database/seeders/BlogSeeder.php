<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Scopes\FilterBlogByAuth;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::withoutGlobalScope(FilterBlogByAuth::class)->insert([
            [
                'title' => 'Eclipse1: the Best of Solana, Ethereum, and Celestia',
                'content' => 'content1',
                'image' => 'img1',
                'category_id' => Category::first()?->id,
                'user_id' => User::first()?->id,
            ],
            [
                'title' => 'blog2 ',
                'content' => 'content2',
                'image' => 'img2',
                'category_id' => Category::first()?->id,
                'user_id' => User::first()?->id,
            ],
        ]);
    }
}
