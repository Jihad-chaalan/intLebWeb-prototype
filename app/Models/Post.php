<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'internship_posts';
    protected $fillable = [
        'company_id',
        'position',
        'technology',
        'photo',
        'description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // public function applications()
    // {
    //     return $this->hasMany(Application::class);
    // }
    public function applications()
    {
        return $this->hasMany(Application::class, 'internship_post_id');
    }
}
