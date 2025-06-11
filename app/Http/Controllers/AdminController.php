<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Post;
use App\Models\InternshipSeeker;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch stats
        $companiesCount = Company::count();
        $seekersCount = InternshipSeeker::count();
        $postsCount = Post::count();
        $pendingRequests = Company::where('verified', '0')->count();

        // Fetch data for each section
        $posts = Post::with('company')->latest()->get();
        $companies = Company::with('posts')->get();
        $seekers = InternshipSeeker::all();

        return view('admin.dashboard', [
            'companiesCount' => $companiesCount,
            'seekersCount' => $seekersCount,
            'postsCount' => $postsCount,
            'pendingRequests' => $pendingRequests,
            'posts' => $posts,
            'companies' => $companies,
            'seekers' => $seekers,
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }



    public function deletePost($id)
    {
        Post::findOrFail($id)->delete();
        return back()->with('success', 'Post deleted successfully.');
    }

    public function verify(Company $company)
    {
        // Update verified column
        $company->verified = 1;
        $company->save();

        return redirect()->back();
    }
}
