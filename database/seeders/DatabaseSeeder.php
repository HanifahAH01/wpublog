<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([CategorySeeder::class, UserSeeder::class, PostSeeder::class]);
        
        

        // User::factory()->create([
        //     'name' => 'Hanifah',
        //     'username' => 'hanifah',
        //     'email' => 'hanifah@gmail.com',
        // ]);

        // Post::create([
        //     'title' => 'Post pertama',
        //     'slug' => 'post-pertama',
        //     'author' => 'Test User',
        //     'body' => 'Ini adalah isi post pertama.',
        // ]);

        // Post::create([
        //     'title' => 'Belajar Laravel',
        //     'slug' => 'belajar-laravel',
        //     'author' => 'Test User',
        //     'body' => 'Laravel memudahkan proses pembuatan aplikasi web.',
        // ]);
    }
}