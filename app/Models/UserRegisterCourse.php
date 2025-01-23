<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRegisterCourse extends Model
{
    use HasFactory;

    protected $table = 'user_register_courses';
    protected $fillable = ['user_id', 'course_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function registerCourse($user_id, $course_id)
    {
        $register = new UserRegisterCourse();
        $register->user_id = $user_id;
        $register->course_id = $course_id;
        $register->save();
        return $register;
    }

}
