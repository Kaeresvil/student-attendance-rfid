<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function attendance(Request $request)
    {

        $input = $request->all();

        Student::create($input);

        return response()->json(['success'=> 'Student Created Successfully']);

    }

    public function show($id)
    {
        $post = Student::with('section')->find($id);
        return response()->json($post);
    }

    public function update($id, Request $request)
    {
        $post = Student::find($id);
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'grade_section_id' => 'required',
            'lrn' => ['required', 'unique:students,lrn,'.$request->id],
        ]);

        $input = $request->all();
        $post->update($input);

        return response()->json(['success'=> 'Student Updated Successfully']);
    }

}
