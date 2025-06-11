<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'internship_seeker_id',
        'internship_post_id',
        'status',
        'applied_at'
    ];

    public function seeker()
    {
        return $this->belongsTo(InternshipSeeker::class, 'internship_seeker_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
