<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'description', 'link', 'internship_seeker_id'];

    public function internshipSeeker()
    {
        return $this->belongsTo(InternshipSeeker::class);
    }
}
