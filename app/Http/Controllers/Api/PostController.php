<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        $post = Post::latest()->get();
        $res = [
            'success' => true,
            'data' => $post,
            'message' => 'List Post',
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:155|unique:posts,title',
            'content' => 'required|string',
            'status'  => 'required',
            'foto'    => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = new Post;
        $post->title   = $request->title;
        $post->slug    = Str::slug($request->title, '-');
        $post->content = $request->get('content');
        $post->status  = $request->status;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('posts', 'public');
            $post->foto = $path;
        }
        $post->save();

        $res = [
            'success' => true,
            'data'    => $post,
            'message' => 'Store Post',
        ];
        return response()->json($res, 201);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Data Not Found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $post,
            'message' => 'Show Post Detail',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => [
                'required',
                'string',
                'max:155',
                Rule::unique('posts', 'title')->ignore($id),
            ],
            'content' => 'required|string',
            'status'  => 'required',
            'foto'    => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        $post->title   = $request->title;
        $post->slug    = Str::slug($request->title, '-');
        $post->content = $request->get('content');
        $post->status  = $request->status;

        if ($request->hasFile('foto')) {
            if ($post->foto && Storage::disk('public')->exists($post->foto)) {
                Storage::disk('public')->delete($post->foto);
            }
            $path = $request->file('foto')->store('posts', 'public');
            $post->foto = $path;
        }
        $post->save();

        $res = [
            'success' => true,
            'data'    => $post,
            'message' => 'Update Post',
        ];
        return response()->json($res, 200);
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }

        if ($post->foto && Storage::disk('public')->exists($post->foto)) {
            Storage::disk('public')->delete($post->foto);
        }

        $post->delete();

        $res = [
            'success' => true,
            'data'    => $post,
            'message' => 'Delete Post',
        ];
        return response()->json($res, 200);
    }
}
