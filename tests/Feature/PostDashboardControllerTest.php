<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

it('updates a post through the dashboard patch route', function () {
    $user = User::factory()->create();
    $currentCategory = Category::factory()->create(['color' => '#ffffff']);
    $newCategory = Category::factory()->create(['color' => '#000000']);
    $post = Post::query()->create([
        'title' => 'Original post title',
        'slug' => 'original-post-title',
        'author_id' => $user->id,
        'category_id' => $currentCategory->id,
        'body' => 'Original body',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch("/dashboard/{$post->slug}", [
            'title' => 'Updated post title',
            'category_id' => $newCategory->id,
            'body' => 'Updated body',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    $post->refresh();

    expect($post->title)->toBe('Updated post title');
    expect($post->slug)->toBe('updated-post-title');
    expect($post->category_id)->toBe($newCategory->id);
    expect($post->body)->toBe('Updated body');
});

it('allows a post to keep its current title while other fields are updated', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['color' => '#ffffff']);
    $post = Post::query()->create([
        'title' => 'Existing post title',
        'slug' => 'existing-post-title',
        'author_id' => $user->id,
        'category_id' => $category->id,
        'body' => 'Original body',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch("/dashboard/{$post->slug}", [
            'title' => 'Existing post title',
            'category_id' => $category->id,
            'body' => 'Updated body',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    expect($post->refresh()->body)->toBe('Updated body');
});
