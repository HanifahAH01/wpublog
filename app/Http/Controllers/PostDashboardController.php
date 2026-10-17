<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostDashboardController extends Controller
{
    /**
     * Display the authenticated user's posts.
     */
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->latest()
            ->where('author_id', Auth::id())
            ->when($request->input('keyword'), function ($query, $keyword) {
                $query->where('title', 'like', '%'.$keyword.'%');
            })
            ->paginate(7)
            ->withQueryString();

        return view('dashboard.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $minBody = 100;

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts,title|min:4|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'required|min:'.$minBody,
        ], [
            'title.required' => 'Field :attribute harus diisi!',
            'category_id.required' => 'Pilih salah satu :attribute',
            'body.required' => ':attribute harus diisi minimal '.$minBody.' karakter atau lebih.',
            'body.min' => ':attribute harus '.$minBody.' karakter atau lebih.',
        ], [
            'title' => 'Judul',
            'category_id' => 'Kategori',
            'body' => 'Konten',
        ]);

        $validated = $validator->validate();

        Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'body' => $validated['body'],
            'author_id' => Auth::id(),
            'category_id' => $validated['category_id'],
        ]);

        return redirect('/dashboard')
            ->with('success', 'Your post has been successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $this->ensurePostBelongsToAuthenticatedUser($post);

        return view('dashboard.show', [
            'title' => 'Detail Post',
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $this->ensurePostBelongsToAuthenticatedUser($post);

        return view('dashboard.edit', [
            'title' => 'Edit Post',
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->ensurePostBelongsToAuthenticatedUser($post);

        $validated = $request->validate([
            'title' => ['required', 'min:4', 'max:255', Rule::unique('posts', 'title')->ignore($post->id)],
            'body' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $post->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'body' => $validated['body'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect('/dashboard')->with('success', 'Your post has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->ensurePostBelongsToAuthenticatedUser($post);
        $post->delete();

        return redirect('/dashboard')->with('success', 'Your post has been removed!');
    }

    private function ensurePostBelongsToAuthenticatedUser(Post $post): void
    {
        abort_unless($post->author_id === Auth::id(), 403);
    }
}
