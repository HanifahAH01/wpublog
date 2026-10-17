<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Web Programming',
            'slug' => 'web-programming',
            'color' => 'bg-red-100',
        ]);
        Category::create([
            'name' => 'Web Design',
            'slug' => 'web-design',
            'color' => 'bg-green-100',
        ]);
        Category::create([
            'name' => 'Mobile Development',
            'slug' => 'mobile-development',
            'color' => 'bg-blue-100',
        ]);
    }
}