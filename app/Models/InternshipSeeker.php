<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipSeeker extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'phone_number',
        'profile_photo',
        'cover_photo',
        'description',
        'github_link',
        'skills',
    ];
    protected $casts = [
        'skills' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
