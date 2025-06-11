<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Post;
use App\Models\InternshipSeeker;
use App\Models\Application;
use App\Models\Project;

class InternshipPostController extends Controller
{
    public function index()
    {
        return view('company.posts');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
        ]);

        $post = new Post($request->all());
        $post->company_id = Auth::guard('company')->id();
        $post->save();

        return response()->json(['message' => 'Internship posted successfully.']);
    }

    public function update(Request $request, $id)
    {
        $post = Post::where('id', $id)->where('company_id', Auth::guard('company')->id())->firstOrFail();
        $post->update($request->all());

        return response()->json(['message' => 'Post updated successfully.']);
    }

    public function destroy($id)
    {
        $post = Post::where('id', $id)->where('company_id', Auth::guard('company')->id())->firstOrFail();
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }
}
