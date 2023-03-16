<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
   
    public function index(Request $request)
    {
        $params = $request->all();

        $orderByColumn = 'updated_at';
        $direction = 'DESC';
        $limit = 15;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['search']) && $params['section'] != 1) {
            $search = $params['search'];
            $query = Student::where('grade_section_id', $params['section'])->where('name', 'LIKE', '%'.$search.'%')->orwhere('lrn', 'LIKE', '%'.$search.'%')->with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        } else if (isset($params['search']) && $params['section'] == 1) {
            $search = $params['search'];
            $query = Student::where('name', 'LIKE', '%'.$search.'%')->orwhere('lrn', 'LIKE', '%'.$search.'%')->with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        }
        else if($params['section'] != 1){
            $query = Student::where('grade_section_id', $params['section'])->with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = Student::with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        }




        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
    }

    public function getall(){
        $grade = Student::all()->toArray();
        return array_reverse($grade);
    }

    public function change(Request $request)
    {
   
        $students = $request->_value;
        $stud_id = [];
        foreach($students as $student){
            array_push($stud_id, $student['id']);
            
        }

        Student::whereIn("id", $stud_id)
        ->update([
            'grade_section_id' => $request->section_id,
        ]);

        return response()->json(['success'=> 'Student Updated Successfully']);
        
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'grade_section_id' => 'required',
            'lrn' => ['required', 'unique:students'],
        ]);

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

    public function delete($id)
    {
        $post = Student::find($id);
        $post->delete();
        return response()->json(['success'=> 'Student Deleted Successfully']);

    } 

}
