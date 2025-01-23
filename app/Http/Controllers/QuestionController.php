<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator; // Add this line

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->name;
        $questions = Question::query();

        $response = $questions->when(!empty($name), function ($q) use ($name) {
            return $q->where('name', 'LIKE', "%{$name}%");
        })->orderBy("id", "DESC")->get();

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
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'option_1'=> 'required|string',
            'option_2'=> 'required|string',
            'option_3'=> 'required|string',
            'option_4'=> 'required|string',
            'is_correct' => 'required|string',
            'question_score' => 'integer',
            'exercise_id' => 'integer|required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $question = Question::create($request->all());

            return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'data' => $question,
                'message' => 'Question created successfully',
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $error) {
            return response()->json([
                'status' => HttpResponse::HTTP_BAD_REQUEST,
                'message' => 'Failed to create question',
                'error' => $error,
            ], HttpResponse::HTTP_BAD_REQUEST);
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
            $question = Question::findOrFail($id);

            return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'data' => $question,
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $error) {
            return response()->json([
                'status' => HttpResponse::HTTP_NOT_FOUND,
                'message' => 'Question not found',
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
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'option_1'=> 'required|string',
            'option_2'=> 'required|string',
            'option_3'=> 'required|string',
            'option_4'=> 'required|string',
            'is_correct' => 'required|string',
            'question_score' => 'integer',
            'exercise_id' => 'integer|required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $question = Question::findOrFail($id);
            $question->update($request->all());

            return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'data' => $question,
                'message' => 'Question updated successfully',
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $error) {
            return response()->json([
                'status' => HttpResponse::HTTP_BAD_REQUEST,
                'message' => 'Failed to update question',
                'error' => $error,
            ], HttpResponse::HTTP_BAD_REQUEST);
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
            $question = Question::findOrFail($id);
            $question->delete();

            return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'message' => 'Question deleted successfully',
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $error) {
            return response()->json([
                'status' => HttpResponse::HTTP_BAD_REQUEST,
                'message' => 'Failed to delete question',
                'error' => $error,
            ], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get questions by exercise ID.
     *
     * @param  int  $exercise_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getQuestionsByExerciseId($exercise_id, Request $request)
    {
        $question_text = $request->search;

        $questions = Question::where('exercise_id', $exercise_id)
            ->when(!empty($question_text), function ($q) use ($question_text) {
                return $q->where('question_text', 'LIKE', "%{$question_text}%");
            })
            ->get();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'data' => $questions
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Get questions by exercise ID and question text.
     *
     * @param  int  $exercise_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getQuestionByName($exercise_id, Request $request)
    {
        $question_text = $request->question_text;

        $questions = Question::where('exercise_id', $exercise_id)
            ->when(!empty($question_text), function ($q) use ($question_text) {
                return $q->where('question_text', 'LIKE', "%{$question_text}%");
            })
            ->get();

        return response()->json([
            'status' => HttpResponse::HTTP_OK,
            'data' => $questions
        ], HttpResponse::HTTP_OK);
    }
}
