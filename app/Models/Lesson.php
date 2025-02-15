<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_name',
        'course_id',
        'video_url',
        'lesson_duration',
        'type',
    ];
    protected $table = 'lessons';

    public function course() {
        return $this->belongsto(Course::class, 'course_id','id');
    }
}
