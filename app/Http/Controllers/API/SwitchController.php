<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\S_Switch;
use Illuminate\Http\Request;

class SwitchController extends Controller
{
    public function get(){
        $sw = S_Switch::where('id',1)->first();
        return response()->json($sw);
    }

    public function set($id, Request $request)
    {
        $sw = S_Switch::find($id);

        $input = $request->all();
        $sw->update($input);

        return response()->json(['success'=> 'Attendance System is already set']);
    }

}
