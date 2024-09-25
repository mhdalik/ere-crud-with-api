<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Mail\NewBlogCreatedMail;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BlogApiController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Blog::with('category', 'tags')->get());
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|min:3|max:200',
                'content' => 'required|string|max:500',
                'category_id' => 'nullable|exists:categories,id',
                'tags' => 'nullable|array',
                'tags.*' => 'nullable|exists:tags,id|distinct',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'validation_errors' => $validator->getMessageBag(),
                ], 422);
            }

            $blog = DB::transaction(function () use ($request) {
                $blog = Blog::create($request->only(['title', 'content', 'category_id']));
                $blog->image = ImageHelper::store($request->image, $blog->id);
                $blog->tags()->attach($request->tags);
                $blog->comments_count = random_int(30, 150);
                $blog->user_id = auth('sanctum')->user()->id;
                $blog->save();
                return $blog;
            });

            dispatch(function () {
                Mail::to('enteradminemailhere@admin.com')->send(new NewBlogCreatedMail());
            })->afterResponse();

            return response()->json([
                'message' => 'Blog created successfully',
                'data' => $blog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $blog = Blog::with('category', 'tags')->findOrFail($id);
            return response()->json($blog);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            if (Blog::where('id', $id)->doesntExist()) {
                return response()->json(['error' => 'Blog not found'], 404);
            }

            $blog = Blog::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|min:3|max:200',
                'content' => 'required|string|max:500',
                'category_id' => 'nullable|exists:categories,id',
                'tags' => 'nullable|array',
                'tags.*' => 'nullable|exists:tags,id|distinct',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'validation_errors' => $validator->getMessageBag(),
                ], 422);
            }

            $blog = DB::transaction(function () use ($blog, $request, $id) {
                $blog->update($request->only(['title', 'content', 'category_id']));
                $blog->tags()->sync($request->tags);

                if ($request->hasFile('image')) {
                    $old_img = $blog->image;
                    $blog->image = ImageHelper::update($request->image, $id, $old_img);
                }
                $blog->save();
                return $blog;
            });

            return response()->json([
                'message' => 'Blog updated successfully',
                'data' => $blog->fresh('category', 'tags'),
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            if (Blog::where('id', $id)->doesntExist()) {
                return response()->json([
                    'error' => 'Blog not found',
                ], 404);
            }

            $blog = Blog::findOrFail($id);

            $is_deleted = DB::transaction(function () use ($id, $blog) {
                ImageHelper::delete($blog->image);
                return $blog->delete();
            });

            return response()->json([
                'message' => 'Blog deleted successfully',
            ], 204);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
            ], 500);
        }
    }
}
