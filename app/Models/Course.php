<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Exercise;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_name',
        'course_description',
        'teacher_id',
        'image_url',
        'type',
        'slug',
        'member_register'

    ];

    public function users() {
        return $this->hasMany(User::class,'id', 'teacher_id');
    }
    public function exercise() {
        return $this->hasMany(Exercise::class,'course_id', 'id');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
