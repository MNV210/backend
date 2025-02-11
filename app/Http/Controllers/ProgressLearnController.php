<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgressLearn;

class ProgressLearnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'integer',
            'course_id' => 'integer'
        ]);

        $progressLearns = ProgressLearn::where('user_id', $request->user_id)
                                       ->where('course_id', $request->course_id)
                                       ->get();
        return response()->json($progressLearns);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            // Add other fields validation as needed
        ]);

        $progressLearn = ProgressLearn::create($request->all());
        return response()->json($progressLearn, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $progressLearn = ProgressLearn::find($id);
        if (is_null($progressLearn)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        return response()->json($progressLearn);
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
        $progressLearn = ProgressLearn::find($id);
        if (is_null($progressLearn)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        $request->validate([
            'user_id' => 'integer',
            'course_id' => 'integer',
            // Add other fields validation as needed
        ]);

        $progressLearn->update($request->all());
        return response()->json($progressLearn);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $progressLearn = ProgressLearn::find($id);
        if (is_null($progressLearn)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        $progressLearn->delete();
        return response()->json(null, 204);
    }

    public function checkHave(Request $request)
    {
        $request->validate([
            'user_id' => 'integer',
            'course_id' => 'integer',
            'lesson_id' => 'integer'
        ]);

        $progressLearns = ProgressLearn::where('user_id', $request->user_id)
                                       ->where('course_id', $request->course_id)
                                       ->where('lesson_id', $request->lesson_id)
                                       ->get();
        return response()->json($progressLearns);
    }
}
