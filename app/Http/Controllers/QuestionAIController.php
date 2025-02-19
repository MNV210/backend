<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionAI;
use App\Models\Lesson;
use Exception;

class QuestionAIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $course_id = Lesson::where('id', $request->lesson_id)->first()->course_id;
            $questions = QuestionAI::where('course_id', $course_id)->where('user_id', $request->user_id)->get();
            return response()->json($questions);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching questions', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'question' => 'required|string|max:255',
                // 'answer' => 'required|string|max:255',
                'user_id' => 'integer',
                'type'=>'string'
            ]);

            $course_id = Lesson::where('id', $request->lesson_id)->first()->course_id;
            $validatedData['course_id'] = $course_id;

            $question = QuestionAI::create($validatedData);
            return response()->json($question, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error storing question', 'error' => $e->getMessage()], 500);
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
            $question = QuestionAI::find($id);
            if (is_null($question)) {
                return response()->json(['message' => 'Question not found'], 404);
            }
            return response()->json($question);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching question', 'error' => $e->getMessage()], 500);
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
        try {
            $validatedData = $request->validate([
                'question' => 'sometimes|required|string|max:255',
                'answer' => 'sometimes|required|string|max:255',
            ]);

            $question = QuestionAI::find($id);
            if (is_null($question)) {
                return response()->json(['message' => 'Question not found'], 404);
            }
            $question->update($validatedData);
            return response()->json($question);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating question', 'error' => $e->getMessage()], 500);
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
            $question = QuestionAI::find($id);
            if (is_null($question)) {
                return response()->json(['message' => 'Question not found'], 404);
            }
            $question->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting question', 'error' => $e->getMessage()], 500);
        }
    }
}
