<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\UserSection;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
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
            $searchable = ['name', 'description'];
            $query = Section::where('name', 'LIKE', '%'.$search.'%' )->orWhere('description', 'LIKE', '%'.$search.'%')->with('grade_level')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = Section::with('grade_level')->orderBy($orderByColumn, $direction)->paginate($limit);
        }

        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
    }
    public function index_reports(Request $request)
    {
        $params = $request->all();
        $user = Auth::user();
        $allSectionsId = $user->allSectionsId();

        $orderByColumn = 'updated_at';
        $direction = 'DESC';
        $limit = 15;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['search'])) {
            $search = $params['search'];
            $searchable = ['name', 'description'];
            if (count($allSectionsId) > 0) {
            $query = Section::whereIn('id',$allSectionsId)->where('name', 'LIKE', '%'.$search.'%' )
            ->orWhereHas('grade_level', function($query) use($search){
                $query->where('grade_level', 'LIKE', '%'.$search.'%');
            })
            ->whereIn('id',$allSectionsId)
           ->with('grade_level')
            ->orderBy($orderByColumn, $direction)->paginate($limit);
            }
        }else{
            if (count($allSectionsId) > 0) {
            $query = Section::whereIn('id',$allSectionsId)->with('grade_level')->orderBy($orderByColumn, $direction)->paginate($limit);
            }else{
                $query = [];
            }
        }

        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
    }

    public function getall(){
        $user = Auth::user();
        $allSectionsId = $user->allSectionsId();
        if (count($allSectionsId) > 0) { 
        $grade = Section::whereIn('id',$allSectionsId)->with('grade_level')->get()->toArray();
        }else{
            $grade = [];
        }
        return array_reverse($grade);
    }

    public function getallNoRestriction(){
        $grade = Section::with('grade_level')->get()->toArray();
        return array_reverse($grade);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'grade_level_id' => 'required',
        ]);

        $input = $request->all();

        $sec = Section::orderBy('id', 'DESC')->first();
        if($sec == ''){
            $input['id'] = 2;
            Section::create($input);
        }else{
            $input['id'] = $sec->id+1;
            Section::create($input);
        }


        // Section::create($input);

        return response()->json(['success'=> 'Section Created Successfully']);

    }

    public function show($id)
    {
        $section = Section::find($id);
        return response()->json($section);
    }

    public function update($id, Request $request)
    {
        $post = Section::find($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'grade_level_id' => 'required',
        ]);

        $input = $request->all();
        $post->update($input);

        return response()->json(['success'=> 'Section Update Successfully']);
    }

    public function delete($id)
    {
        $post = Section::find($id);


        UserSection::where('section_id',$post->id)->delete();
        $students = Student::where('grade_section_id',$post->id)->get();
        $students_lrn = $students->pluck('lrn');
        Student::where('grade_section_id',$post->id)->delete();
        Attendance::whereIn('stud_lrn', $students_lrn)->delete();

        $post->delete();
        return response()->json(['success'=> 'Section Deleted Successfully']);

    } 
}
