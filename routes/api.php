<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\GradeLevelController;
use App\Http\Controllers\API\SectionController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\SwitchController;
use App\Http\Controllers\API\AttendanceController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('attendance', [UserController::class, 'attendance']);
Route::get('getevent', [EventController::class, 'getEvent']);
Route::get('getswitch', [SwitchController::class, 'get']);
Route::get('getStudent/{id}', [StudentController::class, 'getStudent']);
Route::post('attendance', [AttendanceController::class, 'store']);


Route::group(['prefix' => 'page','middleware' => 'auth:sanctum'], function() {

    /// routes for system switch;
    Route::get('switchedit', [SwitchController::class,'get']);
    Route::post('switch/update/{id}', [SwitchController::class,'set']);

    /// routes for users
    Route::post('register', [UserController::class, 'register']);
    Route::get('users', [UserController::class,'Uindex']);
    Route::get('auth_user', [UserController::class,'AuthUser']);
    Route::get('usersedit/{id}', [UserController::class,'Uedit']);
    Route::delete('usersdelete/{id}', [UserController::class,'Udelete']);
    Route::post('users/update/{id}', [UserController::class,'Uupdate']);
    Route::post('users/profileupdate/{id}', [UserController::class,'profileUpdate']);

    /// routes for student
    Route::post('student/add', [StudentController::class, 'store']);
    Route::post('students/change', [StudentController::class, 'change']);
    Route::get('student', [StudentController::class,'index']);
    Route::get('studentedit/{id}', [StudentController::class,'show']);
    Route::delete('studentdelete/{id}', [StudentController::class,'delete']);
    Route::post('student/update/{id}', [StudentController::class,'update']);

    /// routes for event
    Route::post('event/add', [EventController::class, 'store']);
    Route::post('setevent', [EventController::class, 'setEvent']);
    Route::get('all_events', [EventController::class,'getall']);
    Route::get('event', [EventController::class,'index']);
    Route::get('eventedit/{id}', [EventController::class,'show']);
    Route::delete('eventdelete/{id}', [EventController::class,'delete']);
    Route::post('event/update/{id}', [EventController::class,'update']);

    /// routes for roles
    Route::get('roles', [RoleController::class,'Rindex']);
    Route::get('allroles', [RoleController::class,'getall']);
    Route::post('role/add', [RoleController::class,'Radd']);
    Route::post('roleupdate/{id}', [RoleController::class,'Rupdate']);
    Route::get('roleedit/{id}', [RoleController::class,'Redit']);
    Route::delete('roledelete/{id}', [RoleController::class,'Rdelete']);

    /// routes for grade level
    Route::get('grade_level', [GradeLevelController::class,'index']);
    Route::get('all_grade_level', [GradeLevelController::class,'getall']);
    Route::post('grade_level/add', [GradeLevelController::class,'store']);
    Route::get('grade_level_show/{id}', [GradeLevelController::class,'show']);
    Route::post('grade_level_update/{id}', [GradeLevelController::class,'update']);
    Route::delete('grade_level_delete/{id}', [GradeLevelController::class,'delete']);

    /// routes for section
    Route::get('section', [SectionController::class,'index']);
    Route::get('section_reports', [SectionController::class,'index_reports']);
    Route::get('all_section', [SectionController::class,'getall']);
    Route::get('all_section_noRestrict', [SectionController::class,'getallNoRestriction']);
    Route::post('section/add', [SectionController::class,'store']);
    Route::get('section_show/{id}', [SectionController::class,'show']);
    Route::post('section_update/{id}', [SectionController::class,'update']);
    Route::delete('section_delete/{id}', [SectionController::class,'delete']);

    //// routes for reports
    Route::get('/get/student/attendance', [AttendanceController::class, 'getstudentattendance']);

});
