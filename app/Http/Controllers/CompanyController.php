<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{


    // public function index()
    // {
    //     $company = Auth::user()->company;
    //     return view('company-profile', compact('company'));
    // }
    public function index()
    {
        $company = Auth::user()->company;

        if (!$company) {
            // Handle no company case, e.g., show error page or redirect
            abort(404, 'Company not found for this user.');
        }

        $posts = $company->posts()->with('applications.seeker.user')->latest()->get();

        return view('company-profile', compact('company', 'posts'));
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $company = Auth::user()->company;

        if ($request->hasFile('profile_photo')) {

            if ($company->profile_photo) {
                Storage::disk('public')->delete($company->profile_photo);
            }
            $path = $request->file('profile_photo')->store('company_profiles', 'public');
            $company->profile_photo = $path;
            $company->save();
        }

        return redirect()->back();
    }
    public function updateCoverPhoto(Request $request)
    {
        Log::info('Request files:', $request->allFiles());
        Log::info('Request input:', $request->all());

        $request->validate([
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $company = Auth::user()->company;

        if ($request->hasFile('cover_photo')) {
            if ($company->cover_photo) {
                Storage::disk('public')->delete($company->cover_photo);
            }
            $path = $request->file('cover_photo')->store('company_covers', 'public');
            $company->cover_photo = $path;
            $company->save();

            return redirect()->back();
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
    // Update basic company info
    public function updateCompanyInfo(Request $request)
    {
        $company = Auth::user()->company;

        $validated = $request->validate([
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email',
            'website' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $company->update($validated);

        return redirect()->back()->with('success', 'Company Profile information saved successfully ✅');
    }

    public function addInternshipPost(Request $request)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'technology' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB max
        ]);

        $company = Auth::user()->company;

        if (!$company) {
            return response()->json(['error' => 'Company not found or unauthorized'], 403);
        }

        $data = $request->only(['position', 'technology', 'description']);
        $data['company_id'] = $company->id;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('internship_media', 'public');
            $data['photo'] = $path;
        }

        Post::create($data);

        return redirect()->back();
    }

    public function editPost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->validate([
            'position' => 'required|string|max:255',
            'technology' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($post->photo) {
                Storage::disk('public')->delete($post->photo);
            }
            $path = $request->file('photo')->store('posts', 'public');
            $data['photo'] = $path;
        }

        $post->update($data);
        // $post->save();

        return redirect()->back()->with('success', 'Post updated successfully.');
    }

    public function deletePost(Post $post)
    {
        if ($post->photo) {
            Storage::disk('public')->delete($post->photo);
        }

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
