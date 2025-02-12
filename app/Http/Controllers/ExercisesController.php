<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\UserRegisterCourse;
use App\Models\Course; // Add this import
use Illuminate\Http\Response as HttpResponse;

class ExercisesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $user_id = $request->user_id;
        $name = $request->exercise_name;

        // Get the course IDs that the user has registered for
        $registeredCourseIds = UserRegisterCourse::where('user_id', $user_id)->pluck('course_id');

        // Get the existing course IDs
        $existingCourseIds = Course::pluck('id');

        // Get exercises that belong to the registered and existing courses
        $response = $this->getExerciseQuery($name)
            // ->whereIn('course_id', $registeredCourseIds)
            // ->whereIn('course_id', $existingCourseIds) // Add this line
            ->with('courses')
            ->orderBy("id", "DESC")
            ->get();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'data' => $response
        ], HttpResponse::HTTP_OK);
    }

    private function getExerciseQuery($name)
    {
        $cours = Exercise::query();
        return $cours->when(!empty($name), function ($q) use ($name) {
            return $q->where('title', 'LIKE', "%{$name}%");
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'integer',
            'total_question' => 'integer',
            'time' => 'string|max:255'
            // Add other fields and their validation rules as needed
        ]);

        $exercise = Exercise::create($validatedData);
        return response()->json($exercise, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exercise = Exercise::findOrFail($id);
        return response()->json($exercise);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->update($request->all());
        return response()->json($exercise);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Exercise::destroy($id);
        return response()->json(null, 204);
    }

   
}
