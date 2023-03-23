<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function attendance(Request $request)
    {

        $input = $request->all();

        Student::create($input);

        return response()->json(['success'=> 'Student Created Successfully']);

    }
    public function store(Request $request)
    {
        

        $attendance = new Attendance();


        $dateFrom = Carbon::now()->format('Y-m-d 00:00:00');
        $dateTo = Carbon::now()->format('Y-m-d 23:59:59');

        $getattendance = Attendance::whereBetween('created_at',[ $dateFrom, $dateTo])->where('stud_lrn',$request->rfid_code)->where('event_id',$request->event_id)->first();

        if($getattendance != ''){
            switch($request->attendance_type) {
                case(1):
                    if($getattendance->am_time_in == ''){
                        $getattendance->update(['am_time_in' =>  Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s')]);
                    }else{
                       return response()->json(['message'=> 'You had already time in','status' => 'existing']);

                    }
                    break;
                case(2):
                    if($getattendance->pm_time_in == ''){
                        $getattendance->update(['pm_time_in' =>  Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s')]);
                    }else{
                       return response()->json(['message'=> 'You had already time in','status' => 'existing']);

                    }
                    break;
                case(3):
                    if($getattendance->am_time_out == ''){
                        $getattendance->update(['am_time_out' =>  Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s')]);
                    }else{
                       return response()->json(['message'=> 'You had already time out','status' => 'existing']);

                    }
                    break;
                case(4):
                    if($getattendance->pm_time_out == ''){
                        $getattendance->update(['pm_time_out' =>  Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s')]);
                    }else{
                       return response()->json(['message'=> 'You had already time out','status' => 'existing']);

                    }
                    break;
     

            }
        }else{
            $attendance->stud_lrn = $request->rfid_code;
            $attendance->section_id = $request->section_id;
            $attendance->event_id = $request->event_id;
            switch($request->attendance_type) {
                case(1):
                    $attendance->am_time_in = Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s');
                    $attendance->am_time_out = null;
                    $attendance->pm_time_in = null;
                    $attendance->pm_time_out = null;

                    $attendance->save();
                    break;
                case(2):
                    $attendance->am_time_in = null;
                    $attendance->am_time_out = null;
                    $attendance->pm_time_in = Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s');
                    $attendance->pm_time_out = null;

                    $attendance->save();
                    break;
                case(3):
                    $attendance->am_time_in = null;
                    $attendance->am_time_out = Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s');
                    $attendance->pm_time_in = null;
                    $attendance->pm_time_out = null;

                    $attendance->save();
                    break;
                case(4):
                    $attendance->am_time_in = null;
                    $attendance->am_time_out = null;
                    $attendance->pm_time_in = null;
                    $attendance->pm_time_out = Carbon::parse( $request->attendance_date)->format('Y-m-d H:i:s');

                    $attendance->save();
                    break;

            }
        }


        return response()->json(['message'=> 'Student Created Successfully','status' => 'success']);

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
