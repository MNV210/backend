<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRegisterCourse;
use App\Models\Exercise;
use App\Models\Course;
use Illuminate\Http\Response as HttpResponse;

class UserRegisterCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function checkUserRegisterCourse(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $isRegistered = UserRegisterCourse::where('course_id', $validatedData['course_id'])
                                          ->where('user_id', $validatedData['user_id'])
                                          ->exists();

        return response()->json(
            [
                'status' => HttpResponse::HTTP_OK,
                'is_registered' => $isRegistered
            ],
            HttpResponse::HTTP_OK
        );
    }

    public function getCourseByUserIdRegister(Request $request) {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $response = UserRegisterCourse::where('user_id', $validatedData['user_id'])->with('course')->get();

        return response()->json(
            [
                'status' => HttpResponse::HTTP_OK,
                'data' => $response
            ],
            HttpResponse::HTTP_OK
        );
    }
    public function getExerciseByUserId(Request $request) {
        $user_id = $request->user_id;
        $listCourseIds = UserRegisterCourse::where('user_id', $user_id)->pluck('course_id');
        $response = Course::whereIn('id', $listCourseIds)->with('exercise')->orderBy("id", "DESC")->get();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'data' => $response
        ], HttpResponse::HTTP_OK);
    }
}
