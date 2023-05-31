<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\UserSection;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
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
        if (isset($params['search'])) {
            $search = $params['search'];
            $searchable = ['grade_level', 'description'];
            $query = GradeLevel::where('grade_level', 'LIKE', '%'.$search.'%')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = GradeLevel::orderBy($orderByColumn, $direction)->paginate($limit);
        }




        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
    }

    public function getall(){
        $grade = GradeLevel::all()->toArray();
        return array_reverse($grade);
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_level' => 'required',
            'description' => 'required',
        ]);

        $input = $request->all();

        GradeLevel::create($input);

        return response()->json(['success'=> 'Grade Level Created Successfully']);

    }

    public function show($id)
    {
        $post = GradeLevel::find($id);
        return response()->json($post);
    }

    public function update($id, Request $request)
    {
        $post = GradeLevel::find($id);
        $request->validate([
            'grade_level' => 'required',
            'description' => 'required',
        ]);

        $input = $request->all();
        $post->update($input);

        return response()->json(['success'=> 'Grade Level Updated Successfully']);
    }

    public function delete($id)
    {
        $post = GradeLevel::find($id);
        $section = Section::where('grade_level_id', $post->id)->get();
        $section_id = $section->pluck('id');

        Section::whereIn('id',$section_id)->delete();
        UserSection::whereIn('section_id',$section_id)->delete();
        $students = Student::whereIn('grade_section_id',$section_id)->get();
        $students_lrn = $students->pluck('lrn');
        Student::whereIn('grade_section_id',$section_id)->delete();
        Attendance::whereIn('stud_lrn', $students_lrn)->delete();

        $post->delete();
        return response()->json(['success'=> 'Grade Level Deleted Successfully']);

    } 

}

