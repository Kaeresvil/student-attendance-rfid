<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
   
    public function index(Request $request)
    {
        $params = $request->all();

        $orderByColumn = 'updated_at';
        $direction = 'DESC';
        $limit = 15;

        $user = Auth::user();
        $allSectionsId = $user->allSectionsId();
        if (count($allSectionsId) > 0) { 

        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['search']) && $params['section'] != 1) {
            $search = $params['search'];
            $query = Student::whereIn('grade_section_id',$allSectionsId)->where('grade_section_id', $params['section'])->where('name', 'LIKE', '%'.$search.'%')->orwhere('lrn', 'LIKE', '%'.$search.'%')->with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        } else if (isset($params['search']) && $params['section'] == 1) {
            $search = $params['search'];
            $query = Student::whereIn('grade_section_id',$allSectionsId)->where('name', 'LIKE', '%'.$search.'%')->orwhere('lrn', 'LIKE', '%'.$search.'%')->with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        }
        else if($params['section'] != 1){
            $query = Student::whereIn('grade_section_id',$allSectionsId)->where('grade_section_id', $params['section'])->with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = Student::whereIn('grade_section_id',$allSectionsId)->with('section')->orderBy($orderByColumn, $direction)->paginate($limit);
        }
        }else{
            $query = [];
        }


        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
    }

    public function getStudent( $lrn){
        $studen = Student::where('lrn',$lrn)->with('section')->get();
        return response()->json($studen);
    }
    public function getall(){
        $grade = Student::all()->toArray();
        return array_reverse($grade);
    }

    public function change(Request $request)
    {
   
        $students = $request->_value;
        $stud_id = [];
        $stud_lrn = [];
        foreach($students as $student){
            array_push($stud_id, $student['id']);
            array_push($stud_lrn, $student['lrn']);
            
        }

        Student::whereIn("id", $stud_id)
        ->update([
            'grade_section_id' => $request->section_id,
        ]);
        Attendance::whereIn("stud_lrn", $stud_lrn)
        ->update([
            'section_id' => $request->section_id,
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

        Attendance::where("stud_lrn", $request->lrn)
        ->update([
            'section_id' => $request->grade_section_id,
        ]);

        return response()->json(['success'=> 'Student Updated Successfully']);
    }

    public function delete($id)
    {
        $post = Student::find($id);
        $post->delete();

        Attendance::where("stud_lrn", $post->lrn)
        ->delete();
        return response()->json(['success'=> 'Student Deleted Successfully']);

    } 

}
