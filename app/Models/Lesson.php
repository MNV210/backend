<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Courses;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_name',
        'course_id',
        'video_url',
        'lesson_duration',
    ];
    protected $table = 'lessons';

    public function cours() {
        return $this->hasMany(Courses::class,'id', 'course_id');
    }
}
