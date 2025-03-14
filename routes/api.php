<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ExercisesController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserRegisterCourseController;
use App\Http\Controllers\UserAnserQuestionController;
use App\Http\Controllers\ProgressLearnController;
use App\Http\Controllers\QuestionAIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login',[LoginController::class,'login']);
Route::post('/register',[LoginController::class,'register']);
Route::apiResource('/users',UserController::class);
Route::apiResource('/courses',CoursController::class);
Route::apiResource('/lessons',LessonController::class);
Route::apiResource('/exercises',ExercisesController::class);
Route::apiResource('/questions',QuestionController::class);
Route::get('/questions/exercise/{exercise_id}', [QuestionController::class, 'getQuestionsByExerciseId']);
Route::post('/questions/exercise_id/{exercise_id}/search', [QuestionController::class, 'getQuestionByName']);
Route::post('/import', [ImportController::class, 'import'])->name('import');
Route::get('/export/{exercise_id}', [ImportController::class, 'export'])->name('export');
Route::post('/get_lesson_by_course', [LessonController::class, 'getLessonByCourseId']);
Route::post('/register_course', [CoursController::class, 'registrationCourse']);
Route::post('/check_user_register', [UserRegisterCourseController::class, 'checkUserRegisterCourse']);
Route::post('/get_course_by_user', [UserRegisterCourseController::class, 'getCourseByUserIdRegister']);
Route::post('list_course_user',[UserRegisterCourseController::class,'getExerciseByUserId']);
Route::post('user_answer_question',[UserAnserQuestionController::class,'create']);
Route::post('history_make_exercise',[UserAnserQuestionController::class,'getHistoryUserMakeExercise']);
Route::post('/lesson/update-video-URL',[LessonController::class,'updateVideoURL']);
Route::apiResource('/progress-learn',ProgressLearnController::class);
Route::get('/check-lesson-completed',[ProgressLearnController::class,'checkHave']);
Route::post('/get_exercise_by_course_id',[UserRegisterCourseController::class,'getExercisesByCourseId']);

Route::post('/course_user_register',[LessonController::class,'getLessonUserRegister']);
Route::post('/change_password',[UserController::class,'changePassword']);

Route::apiResource('/question_ai',QuestionAIController::class);
Route::post('/courses/update-file-training',[CoursController::class,'updateFileTrainingURL']);
Route::get('/role_not_user',[UserController::class,'getUserWithRoleNotUser']);


Route::post('/upload',[UploadController::class,'uploadImageToS3']);



