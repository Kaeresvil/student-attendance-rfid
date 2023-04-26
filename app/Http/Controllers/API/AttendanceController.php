<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

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

    public function getstudentattendance(Request $request)
    {
        $params = $request->all();

        $orderByColumn = 'created_at';
        $direction = 'DESC';
        $limit = 15;

        $user = Auth::user();


        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['search']) ) {
            $search = $params['search'];
            if($params['forall'] == 'false'){
                if($params['dateFormat'] != 'daily'){
                $query = Attendance::where('stud_lrn',$params['id'])->where('event_id',$params['event_id'])->whereBetween('created_at', [$params['dateFrom'], $params['dateTo']])->where('stud_lrn', 'LIKE', '%'.$search.'%')->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit);
                }else{
                $query = Attendance::where('stud_lrn',$params['id'])->where('event_id',$params['event_id'])->whereDate('created_at',$params['dateFrom'])->where('stud_lrn', 'LIKE', '%'.$search.'%')->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit);   
                }
            }else{
                if($params['dateFormat'] != 'daily'){
                $query = Attendance::where('section_id',$params['id'])->where('event_id',$params['event_id'])->whereBetween('created_at', [$params['dateFrom'], $params['dateTo']])->where('stud_lrn', 'LIKE', '%'.$search.'%')->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit);
                }else{
                  $query = Attendance::where('section_id',$params['id'])->where('event_id',$params['event_id'])->whereDate('created_at',$params['dateFrom'])->where('stud_lrn', 'LIKE', '%'.$search.'%')->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit);   
                }
            }
         }else{
            if($params['forall'] == 'false'){
                if($params['dateFormat'] != 'daily'){
                 $query = Attendance::where('stud_lrn',$params['id'])->where('event_id',$params['event_id'])->whereBetween('created_at', [$params['dateFrom'], $params['dateTo']])->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit);
                }else{
                    $query = Attendance::where('stud_lrn',$params['id'])->where('event_id',$params['event_id'])->whereDate('created_at', $params['dateFrom'])->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit);
                }
            }else{
                if($params['dateFormat'] != 'daily'){
                $query = Attendance::where('section_id',$params['id'])->where('event_id',$params['event_id'])->whereBetween('created_at', [$params['dateFrom'], $params['dateTo']])->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit); 
                }else{
                    $query = Attendance::where('section_id',$params['id'])->where('event_id',$params['event_id'])->whereDate('created_at',$params['dateFrom'])->with('student','event')->orderBy($orderByColumn, $direction)->paginate($limit);  
                } 
            }
        }

        return response()->json([
            'message' => 'Student Attendance Fetch Successfully',
            'data' => $query
        ], 200);


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

    public function groupBarChart()
    {
        $user = Auth::user();
        $allSectionsId = $user->allSectionsId();

        $grades = GradeLevel::all();

        $sections = Section::all();

        $gradelevel = [];

        $Series = [];
        $OnTime = [];
        $Late = [];
        $Absentees = [];




        foreach($grades as $grade){

            foreach($sections as $section){
                $attendances = Attendance::where('section_id', $section->id)->get();

            }
            if($section->grade_level_id === $grade->id){
                array_push($OnTime, $attendances->count());
            }else{
                array_push($OnTime, 0);
            }

        array_push($gradelevel, $grade->grade_level);
        }





        return response()->json([
            'success'=> 'Student Updated Successfully', 
            'grade' => $gradelevel,
            'attendance' => $OnTime
    
    ]);
    }
    public function PieChart(Request $request)
    {
        $params = $request->all();
        $dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $timeTo ='07:45:00';
        $students = Student::all();
            if($params['type'] === 'reports'){
                $Ontime = Attendance::whereTime('am_time_in','<',$timeTo)->whereBetween('created_at',[ $dateFrom, $dateTo])->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
                $Late = Attendance::whereTime('am_time_in','>',$timeTo)->whereBetween('created_at',[ $dateFrom, $dateTo])->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
            }else if($params['type'] === 'students-reports'){
                $Ontime = Attendance::where('section_id',$params['section'])->whereTime('am_time_in','<',$timeTo)->whereBetween('created_at',[ $dateFrom, $dateTo])->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
                $Late = Attendance::where('section_id',$params['section'])->whereTime('am_time_in','>',$timeTo)->whereBetween('created_at',[ $dateFrom, $dateTo])->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
            }



        return response()->json([
            'success'=> 'Student Updated Successfully', 
            'series' => [$Ontime->count(),$Late->count(),],
    
    ]);
    }

    public function BarChart(Request $request)
    {
        $params = $request->all();
        $now = Carbon::now()->format('Y-m-d');
        // $dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $timeTo ='07:45:00';
        $timeToPM ='13:15:00';
      

        if($params['type'] === 'reports'){
            $students = Student::all();
            if(Carbon::now()->format('H') <= 12){
                $Ontime = Attendance::whereTime('am_time_in','<',$timeTo)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
                $Late = Attendance::whereTime('am_time_in','>',$timeTo)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
                $absent =  $students->count() - ($Ontime->count() + $Late->count());
            }
            if(Carbon::now()->format('H') >= 13){
                $Ontime = Attendance::whereTime('pm_time_in','<',$timeToPM)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
                $Late = Attendance::whereTime('pm_time_in','>',$timeToPM)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                    $attendance->where('event_name', 'Class Attendance');
                })->get();
                $absent =  $students->count() - ($Ontime->count() + $Late->count()); 
            }
        }else if($params['type'] === 'students-reports'){
            $students = Student::where('grade_section_id',$params['section'])->get();
         if(Carbon::now()->format('H') <= 12){
            $Ontime = Attendance::where('section_id',$params['section'])->whereTime('am_time_in','<',$timeTo)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                $attendance->where('event_name', 'Class Attendance');
            })->get();
            $Late = Attendance::where('section_id',$params['section'])->whereTime('am_time_in','>',$timeTo)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                $attendance->where('event_name', 'Class Attendance');
            })->get();
            $absent =  $students->count() - ($Ontime->count() + $Late->count());
        }
         if(Carbon::now()->format('H') >= 13){
            $Ontime = Attendance::where('section_id',$params['section'])->whereTime('pm_time_in','<',$timeToPM)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                $attendance->where('event_name', 'Class Attendance');
            })->get();
            $Late = Attendance::where('section_id',$params['section'])->whereTime('pm_time_in','>',$timeToPM)->whereDate('created_at',$now)->whereHas('event', function($attendance){
                $attendance->where('event_name', 'Class Attendance');
            })->get();
            $absent =  $students->count() - ($Ontime->count() + $Late->count());
        }
     }


        return response()->json([
            'success'=> 'Student Updated Successfully', 
            '$now'=> $now, 
            'series' => [[
                'name'=> 'Total Students',
                'data'=> [$Ontime->count(),$Late->count(),$absent]]
            ],
            'Carbon::now()->format()' => Carbon::now()->format('H')
    
    ]);
    }



}
