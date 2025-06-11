<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Post;
use App\Models\Company;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InternshipSeekerController extends Controller
{
    public function showHome(Request $request)
    {
        $query = Post::with('company');

        // If a company_id is provided via GET, filter posts by that company
        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $posts = $query->latest()->get();
        $suggestedCompanies = Company::limit(5)->get();

        $seeker = Auth::user()->seeker;
        $appliedPostIds = $seeker
            ? $seeker->applications->pluck('internship_post_id')->toArray()
            : [];
        $skills = $seeker->skills ?? [];

        return view('seeker.home', compact('posts', 'suggestedCompanies', 'appliedPostIds', 'skills'));
    }

    // Show seeker profile page
    public function showProfile()
    {
        $seeker = Auth::user()->seeker;
        $projects = $seeker ? $seeker->projects : collect();
        $skills = $seeker->skills ?? [];
        return view('seeker.profile', compact('seeker', 'projects', 'skills'));
    }

    public function updatePersonalInfo(Request $request)
    {
        $seeker = Auth::user()->seeker;

        $validated = $request->validate([
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email',
            'github_link' => 'nullable|string',
        ]);

        $seeker->update($validated);

        return redirect()->back()->with('success', 'Your Profile information saved successfully ✅');
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $seeker = Auth::user()->seeker;

        if ($request->hasFile('profile_photo')) {

            if ($seeker->profile_photo) {
                Storage::disk('public')->delete($seeker->profile_photo);
            }
            $path = $request->file('profile_photo')->store('seeker_profiles', 'public');
            $seeker->profile_photo = $path;
            $seeker->save();
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

        $seeker = Auth::user()->seeker;

        if ($request->hasFile('cover_photo')) {
            if ($seeker->cover_photo) {
                Storage::disk('public')->delete($seeker->cover_photo);
            }
            $path = $request->file('cover_photo')->store('seeker_covers', 'public');
            $seeker->cover_photo = $path;
            $seeker->save();

            return redirect()->back();
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }



    public function addProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
        ]);

        Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'link' => $validated['link'] ?? null,
            'internship_seeker_id' => Auth::user()->seeker->id,
        ]);

        return redirect()->back()->with('success', 'Project added successfully.✅');
    }

    public function removeProject($id)
    {
        $project = Project::findOrFail($id);

        // Check ownership
        if ($project->internship_seeker_id != Auth::user()->seeker->id) {
            abort(403);
        }

        $project->delete();

        return redirect()->back()->with('success', 'Project removed successfully.✅');
    }

    public function saveSkills(Request $request)
    {
        $seeker = Auth::user()->seeker;

        $skills = $request->input('skills'); // comma separated string

        if (!$skills) {
            return back()->with('error', 'No skills received.');
        }

        $newSkillsArray = explode(',', $skills);

        // Get old skills as array (if null, empty array)
        $oldSkillsArray = $seeker->skills ?? [];

        // Merge old and new skills
        $allSkills = array_unique(array_merge($oldSkillsArray, $newSkillsArray));

        $seeker->skills = $allSkills;
        $seeker->save();

        return back()->with('success', 'Skills saved successfully!');
    }

    public function removeSkill(Request $request)
    {
        $request->validate([
            'skill' => 'required|string',
        ]);

        // Or however you get the current user
        $seeker = Auth::user()->seeker;

        $skills = $seeker->skills ?? [];

        $updated = array_filter($skills, fn($s) => $s !== $request->skill);

        $seeker->skills = array_values($updated); // Reindex the array
        $seeker->save();

        return back()->with('success', 'Skill removed.');
    }

    public function applyToPost(Request $request, Post $post)
    {
        $seeker = Auth::user()->seeker;

        if (!$seeker) {
            return redirect()->back()->with('error', 'Seeker profile not found.');
        }

        // Check if already applied
        $alreadyApplied = Application::where('internship_post_id', $post->id)
            ->where('internship_seeker_id', $seeker->id)
            ->exists();

        if ($alreadyApplied) {
            return redirect()->back()->with('error', 'You have already applied to this post.');
        }

        Application::create([
            'internship_post_id' => $post->id,
            'internship_seeker_id' => $seeker->id,
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully.');
    }
    public function search(Request $request)
    {
        $query = $request->input('q');

        $results = Company::where('name', 'like', '%' . $query . '%')
            ->limit(5)
            ->get(['id', 'name']); // Only return necessary fields

        return response()->json($results);
    }
}
