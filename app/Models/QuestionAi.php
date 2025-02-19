<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAi extends Model
{
    use HasFactory;
    protected $table = 'question_ais';

    // protected $table 
    protected $fillable = [
        'user_id',
        'course_id',
        'question',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
