<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Company;
use App\Models\Seeker;

class HomeController extends Controller
{
    public function index()
    {
        $seeker = Auth::user(); // Assuming 'seekers' use Laravel auth

        // Fetch active posts with company info
        $posts = Post::with('company')
            ->where('status', 'active')
            ->latest()
            ->take(10)
            ->get();

        // Fetch some suggested companies (e.g., top 5)
        $suggestedCompanies = Company::inRandomOrder()->take(5)->get();

        return view('seeker-home', [
            'seeker' => $seeker,
            'posts' => $posts,
            'suggestedCompanies' => $suggestedCompanies,
        ]);
    }
}
