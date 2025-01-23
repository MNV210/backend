<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'total_question',
        'time',
    ];

    public function courses() {
        return $this->belongsTo(Course::class,'course_id', 'id');
    }
}
