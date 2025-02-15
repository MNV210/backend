<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course; // Add this line
use App\Models\UserRegisterCourse; // Add this line
use Illuminate\Http\Response as HttpResponse;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->lesson_name;
        $lessons = Lesson::query();

        $response = $lessons->when(!empty($name), function ($q) use ($name) {
                return $q->where('lesson_name', 'LIKE', "%{$name}%");
        })->with('course')
        ->orderBy("id","DESC")->get();

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
            'lesson_name' => 'required|string|max:255',
            'lesson_duration' => 'nullable|string',
            'video_url' => "string",
            'course_id' => 'required|integer',
            'lesson_video' => 'nullable|file|mimes:mp4,mov,ogg,qt',
            'type' => 'nullable|string',
            // Add other fields as necessary
        ]);

        try {
            $result = Lesson::create($validatedData);

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_CREATED,
                    'message' => 'Create Lesson successfully!',
                    'data' => $result
                ],
                HttpResponse::HTTP_CREATED
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error in create Lesson',
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
            $lesson = Lesson::with('course')->findOrFail($id);

            return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'data' => $lesson
            ], HttpResponse::HTTP_OK);

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_NOT_FOUND,
                'message' => 'Lesson not found',
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
            'lesson_name' => 'required|string|max:255',
            'lesson_description' => 'nullable|string',
            'course_id' => 'required|integer',
            // Add other fields as necessary
        ]);

        try {
            $lesson = Lesson::findOrFail($id);
            $lesson->update($validatedData);

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_OK,
                    'message' => 'Lesson updated successfully!',
                    'data' => $lesson
                ],
                HttpResponse::HTTP_OK
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error in updating lesson',
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
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();

            return response()->json(
                [
                    'status' => HttpResponse::HTTP_OK,
                    'message' => 'Lesson deleted successfully!'
                ],
                HttpResponse::HTTP_OK
            );

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error in deleting lesson',
                'error' => $error,
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLessonByCourseId(Request $request)
    {
        $course_id = $request->course_id;
        $lessons = Lesson::where('course_id', $course_id)->get();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'data' => $lessons
        ], HttpResponse::HTTP_OK);
    }

    public function updateVideoURL(Request $request) {
        $lesson = Lesson::findOrFail($request->lesson_id);
        $lesson->video_url = $request->video_url;
        $lesson->save();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'message' => 'Update video url successfully!'
        ], HttpResponse::HTTP_OK);
    }

    public function updateFileURL(Request $request) {
        $lesson = Lesson::findOrFail($request->lesson_id);
        $lesson->file_url = $request->file_url;
        $lesson->save();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'message' => 'Update file url successfully!'
        ], HttpResponse::HTTP_OK);
    }

    public function getLessonUserRegister(Request $request) {
        $lesson = UserRegisterCourse::where('user_id', $request->user_id)
                                    ->with('course')    
                                    // ->with('user')
                                    ->get();

        return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'data' => $lesson
            ], HttpResponse::HTTP_OK);
    }
}
