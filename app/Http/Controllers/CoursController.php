<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\UserRegisterCourse;

class CoursController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->query('name'); // Use query method to get the parameter
        $cours = Course::query();

        $response = $cours->when(!empty($name), function ($q) use ($name) {
                return $q->where('course_name', 'LIKE', "%{$name}%");
        })->with('users')->orderBy("id","DESC")->get();

        if ($response->isEmpty()) {
            return response()->json([
                'status' => HttpResponse::HTTP_NO_CONTENT,
                'message' => 'No courses found'
            ], HttpResponse::HTTP_NO_CONTENT);
        }

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'data' => $response
        ], HttpResponse::HTTP_OK);
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
            'course_name' => 'required|string|max:255',
            'course_description' => 'nullable|string',
            'teacher_id' => 'required|integer',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'string',
            'slug' => 'string',
        ]);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('image_url', 'public');
            $validatedData['image_url'] = $path;
        }

        $validatedData['slug'] = Str::slug($validatedData['course_name'], '-');

        try {
            $result = Course::create($validatedData);

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_CREATED,
                    'message' => 'Create Course successfully!',
                    'data' => $result
                ],
                HttpResponse::HTTP_CREATED
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error in create Course',
                'error' => $error,
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $course = Course::with('users')->findOrFail($id);

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_OK,
                    'data' => $course
                ],
                HttpResponse::HTTP_OK
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_NOT_FOUND,
                'message' => 'Course not found',
                'error' => $error,
            ], HttpResponse::HTTP_NOT_FOUND);
        }
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
        $validatedData = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_description' => 'nullable|string',
            'teacher_id' => 'required|integer',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Add other fields as necessary
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validatedData['thumbnail'] = $path;
        }

        $validatedData['slug'] = Str::slug($validatedData['course_name'], '-');

        try {
            $course = Course::findOrFail($id);
            $course->update($validatedData);

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_OK,
                    'message' => 'Course updated successfully!',
                    'data' => $course
                ],
                HttpResponse::HTTP_OK
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error in updating course',
                'error' => $error,
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_OK,
                    'message' => 'Course deleted successfully!'
                ],
                HttpResponse::HTTP_OK
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error in deleting course',
                'error' => $error,
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function registrationCourse(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {
            $register = new UserRegisterCourse();
            $register->user_id = $validatedData['user_id'];
            $register->course_id = $validatedData['course_id'];
            $register->save();

            $course = Course::findOrFail($validatedData['course_id']);
            $course->member_register = $course->member_register + 1;
            $course->save();

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_OK,
                    'message' => 'User registered to course successfully!'
                ],
                HttpResponse::HTTP_OK
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error in registering user to course',
                'error' => $error,
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
