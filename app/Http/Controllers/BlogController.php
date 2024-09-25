<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Mail\NewBlogCreatedMail;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('blogs', [
            'blogs' => Blog::with('category', 'tags')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog_create', [
            'categories' => Category::get(),
            'tags' => Tag::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|min:3|max:200',
            'content' => 'required|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|exists:tags,id|distinct',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg',
        ]);

        $blog = Blog::create($request->only(['title', 'content', 'category_id']));
        $blog->comments_count = random_int(30, 150);
        $blog->user_id = Auth::user()->id;
        $blog->image = ImageHelper::store($request->image, $blog->id);
        $blog->tags()->attach($request->tags);
        $blog->save();

        dispatch(function () {
            Mail::to('enteradminemailhere@admin.com')->send(new NewBlogCreatedMail());
        })->afterResponse();

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('blog_show', [
            'blog' => Blog::with('category', 'tags')->findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('blog_edit', [
            'blog' => Blog::with('category', 'tags')->findOrFail($id),
            'categories' => Category::get(),
            'tags' => Tag::get(),
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer',
            'tags' => 'required|array',
            'tags.*' => 'integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $image = $blog->image;
        if ($request->hasFile('image')) {
            $image = ImageHelper::update($request->image, $blog->id, $image);
        }

        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $image,
        ]);
        $blog->tags()->sync($request->tags);

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return redirect()->route('blogs.index')->with('danger', 'Blog deleted successfully.');
    }
}
