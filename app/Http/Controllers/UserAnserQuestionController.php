<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAnswerQuestion;
use App\Models\HistoryMakeExercise;
use App\Models\Question;

class UserAnserQuestionController extends Controller
{
    //

    public function create(Request $request)
    {
        // Kiểm tra tính hợp lệ của dữ liệu đầu vào
        if (!$request->has(['answers', 'exercise_id', 'user_id']) || !is_array($request->answers)) {
            return response()->json(['error' => 'Invalid input data'], 400);
        }

        $totalScore = 0;

        // Lặp qua từng answer trong answers
        foreach ($request->answers as $questionId => $answerText) {
            // Lấy câu hỏi từ cơ sở dữ liệu
            $question = Question::where('exercise_id', $request->exercise_id)
                                ->where('id', $questionId)
                                ->first();

            if ($question) {
                $score = $answerText == $question->is_correct ? 1 : 0;
                $totalScore += $score;

                UserAnswerQuestion::create([
                    'question_id' => $questionId,
                    'user_id' => $request->user_id,
                    'answer' => $answerText,
                ]);
            }
        }

        // Lưu lịch sử làm bài tập
        HistoryMakeExercise::create([
            'user_id' => $request->user_id,
            'exercise_id' => $request->exercise_id,
            'score' => $totalScore,
            'correct_answer' => $totalScore,
        ]);

        return response()->json([
            'data' => [
                'total_score' => $totalScore,
            ],
            'message' => 'Answers created successfully'
        ]);
    }

    public function getHistoryUserMakeExercise(Request $request) {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'exercise_id' => 'required|integer|exists:exercises,id',
        ]);

        $response = HistoryMakeExercise::where('user_id', $validatedData['user_id'])->where('exercise_id', $validatedData['exercise_id'])
        ->with('exercise')
        ->with('user')
        ->first();

        return response()->json([
            'status' => 200,
            'data' => $response
        ]);
    }
}
