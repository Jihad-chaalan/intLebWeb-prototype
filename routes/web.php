<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InternshipSeekerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\InternshipSeeker;
use Random\IntervalBoundary;



Route::get('/', function () {
    return redirect()->route('register');
});



Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::get('/search-companies', [InternshipSeekerController::class, 'search'])->name('companies.search');


// ----------- ADMIN ROUTES -------------
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/admin/posts/{id}', [AdminController::class, 'destroy'])->name('admin.posts.destroy');
    Route::post('/admin/company/{company}/verify', [AdminController::class, 'verify'])->name('admin.company.verify');
});

// ----------- COMPANY ROUTES-------------
Route::middleware(['auth'])->group(function () {
    Route::get('/company', [CompanyController::class, 'index'])->name('company');
    Route::put('/company/update-profile', [CompanyController::class, 'updateCompanyInfo'])->name('company.updateCompanyInfo');
    Route::post('/company/update-cover-photo', [CompanyController::class, 'updateCoverPhoto'])->name('company.updateCoverPhoto');
    Route::post('/company/update-profile-picture', [CompanyController::class, 'updateProfilePicture'])->name('company.updateProfilePicture');
    Route::post('/company/posts', [CompanyController::class, 'addInternshipPost'])->name('company.addInternshipPost');
    Route::put('/company/posts/{id}', [CompanyController::class, 'editPost'])->name('company.editPost');
    Route::delete('/company/posts/{post}', [CompanyController::class, 'deletePost'])->name('company.deletePost');
});

// ----------- SEEKER ROUTES-------------
Route::middleware(['auth'])->group(function () {
    Route::get('/seeker/home', [InternshipSeekerController::class, 'showHome'])->name('seeker.home');
    Route::get('/seeker/profile', [InternshipSeekerController::class, 'showProfile'])->name('seeker.profile');
    Route::put('/seeker/seeker-profile', [InternshipSeekerController::class, 'updatePersonalInfo'])->name('seeker.updateSeekerInfo');
    Route::post('/seeker/update-cover-photo', [InternshipSeekerController::class, 'updateCoverPhoto'])->name('seeker.updateCoverPhoto');
    Route::post('/seeker/update-profile-picture', [InternshipSeekerController::class, 'updateProfilePicture'])->name('seeker.updateProfilePicture');
    Route::post('/seeker/addProject', [InternshipSeekerController::class, 'addProject'])->name('seeker.addProject');
    Route::delete('/seeker/project/{id}', [InternshipSeekerController::class, 'removeProject'])->name('seeker.removeProject');
    Route::post('/seeker/apply/{post}', [InternshipSeekerController::class, 'applyToPost'])->name('seeker.applyToPost');
    Route::post('/save-skills', [InternshipSeekerController::class, 'saveSkills']);
    Route::delete('/remove-skill', [InternshipSeekerController::class, 'removeSkill'])->name('skills.remove');
});


















// // Default auth routes (register, login, logout, etc.)

// Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
// Route::post('/login', [AuthenticatedSessionController::class, 'store']);
// Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
// Route::post('/register', [RegisteredUserController::class, 'store']);
// Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// // ----------- ADMIN ROUTES -------------

// Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('auth')->name('dashboard');
// Route::delete('/admin/posts/{id}', [AdminController::class, 'destroy'])->name('admin.posts.destroy');
// Route::post('/admin/company/{company}/verify', [AdminController::class, 'verify'])->name('admin.company.verify');

// // ----------- COMPANY ROUTES -------------

// Route::get('/company', [CompanyController::class, 'index'])->name('company');
// Route::put('/company/update-profile', [CompanyController::class, 'updateCompanyInfo'])->name('company.updateCompanyInfo');
// Route::post('/company/update-cover-photo', [CompanyController::class, 'updateCoverPhoto'])->name('company.updateCoverPhoto');
// Route::post('/company/update-profile-picture', [CompanyController::class, 'updateProfilePicture'])->name('company.updateProfilePicture');
// // Route::post('/company/internship-posts', [CompanyController::class, 'addInternshipPost'])->name('company.addInternshipPost');
// Route::post('/company/posts', [CompanyController::class, 'addInternshipPost'])->name('company.addInternshipPost');

// Route::put('/company/posts/{id}', [CompanyController::class, 'editPost'])->name('company.editPost');
// Route::delete('/company/posts/{post}', [CompanyController::class, 'deletePost'])->name('company.deletePost');



// // ----------- Seeker ROUTES -------------

// Route::get('/seeker/home', [InternshipSeekerController::class, 'showHome'])->name('seeker.home');
// Route::get('/seeker/profile', [InternshipSeekerController::class, 'showProfile'])->name('seeker.profile');
// Route::put('/seeker/seeker-profile', [InternshipSeekerController::class, 'updatePersonalInfo'])->name('seeker.updateSeekerInfo');
// Route::post('/seeker/update-cover-photo', [InternshipSeekerController::class, 'updateCoverPhoto'])->name('seeker.updateCoverPhoto');
// Route::post('/seeker/update-profile-picture', [InternshipSeekerController::class, 'updateProfilePicture'])->name('seeker.updateProfilePicture');
// Route::middleware(['auth'])->post('/seeker/addProject', [InternshipSeekerController::class, 'addProject'])->name('seeker.addProject');
// Route::delete('/seeker/project/{id}', [InternshipSeekerController::class, 'removeProject'])->name('seeker.removeProject');
// Route::middleware(['auth'])->post('/seeker/apply/{post}', [InternshipSeekerController::class, 'applyToPost'])->name('seeker.applyToPost');
// Route::get('/search-companies', [InternshipSeekerController::class, 'search'])->name('companies.search');
// Route::post('/save-skills', [InternshipSeekerController::class, 'saveSkills'])->middleware('auth');
// Route::delete('/remove-skill', [InternshipSeekerController::class, 'removeSkill'])->name('skills.remove');



require __DIR__ . '/auth.php';
