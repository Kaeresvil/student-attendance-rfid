<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

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

    public function getall(){
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

        Section::create($input);

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
        $post->delete();
        return response()->json(['success'=> 'Section Deleted Successfully']);

    } 
}
