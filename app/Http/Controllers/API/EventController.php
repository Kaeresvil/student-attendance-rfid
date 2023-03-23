<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Student;
use Illuminate\Http\Request;

class EventController extends Controller
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
            $query = Event::where('event_name', 'LIKE', '%'.$search.'%')->orwhere('event_date', 'LIKE', '%'.$search.'%')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = Event::orderBy($orderByColumn, $direction)->paginate($limit);
        }

        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
    }

    public function getEvent(Request $request){
       
        $event = Event::where("isSet", 1)->get();
        return response()->json([
            'message' => 'Success',
            'data' => $event
        ], 200);


    }
    public function setEvent(Request $request){
       
        Event::where("isSet", 1)
        ->update([
            'isSet' => 0,
        ]);
        Event::where("id", $request->event_id)
        ->update([
            'isSet' => 1,
        ]);

        return response()->json(['success'=> 'Event Set Successfully']);

    }

    public function getall(){
        $grade = Event::get()->toArray();
        return array_reverse($grade);
    }

    public function store (Request $request)
    {
        $request->validate([
            'event_name' => 'required',
            'event_date' => 'required',
            'event_description' => 'required',
        ]);

        $input = $request->all();

        Event::create($input);

        return response()->json(['success'=> 'Student Created Successfully']);
    }

    public function show($id)
    {
        $post = Event::find($id);
        return response()->json($post);
    }

    public function update($id, Request $request)
    {
        $post = Event::find($id);
        // $request->validate([
        //     'event_name' => 'required',
        //     'event_date' => 'required',
        //     'event_description' => 'required'
        // ]);

        // $input = $request->all();
        $post->update($input);

        return response()->json(['success'=> 'Student Updated Successfully']);
    }

    public function delete($id)
    {
        $post = Event::find($id);
        $post->delete();
        return response()->json(['success'=> 'Student Deleted Successfully']);

    } 
}
