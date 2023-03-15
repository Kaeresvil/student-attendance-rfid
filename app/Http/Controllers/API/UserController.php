<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSection;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\RelationNotFoundException;
// use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function Uindex(Request $request)
    {
        // $users = User::with('role')->get();
        // return response()->json($users);

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
            $query = User::where('name', 'LIKE', '%'.$search.'%' )->with('role')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = User::with('role')->orderBy($orderByColumn, $direction)->paginate($limit);
        }

        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
 
    }


    public function Udelete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['success'=> 'User Deleted Successfully']);

    }


    public function Uedit($id)
    {
        $user = User::with('sections')->find($id);
        return response()->json($user);
    }


    public function Uupdate( Request $request , $id)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255','unique:users,email,$request->id,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$request->id],
            'role_type_id' => ['required'],
        ]);
       


            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_type_id = $request->role_type_id;


         if($request->role_type_id != null){
            $user->update();

            $locations = $request->section;
            foreach ($locations as $location) {
                UserSection::whereNotIn('section_id', $locations)->where('user_id', '=', $user->id)->delete();
                UserSection::firstOrCreate([
                    'section_id' => $location,
                    'user_id' => $user->id,
                ]);
            }

            $success = true;
            $message = "User Updated Successfully";
         }else{
            $success = false;
            $message = "Failed to process data!";
         }
          

     
        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);


        
    }










//// for login and craete users

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials)) {
            $success = true;
            $message = "User login successfully";
       
        } else {
            $success = false;
            $message = "Login Failed! Please try again.";
        }

        $response = [
            'success' => $success,
            'message' => $message,
    
        ];

      
        return response()->json($response);
    }



    public function register(Request $request)
    {
    
        $request->validate([
            'name' => ['required', 'string', 'max:255','unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role_type_id' => ['required'],
            'confirmpassword' => ['required', 'string', 'min:8'],
        ]);
       

            $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role_type_id = $request->role_type_id;
                $user->password = Hash::make($request->password);

               
                  if($request->password == $request->confirmpassword){

                 
              if( $user->save()){
                $locations = $request->section;
                foreach ($locations as $location) {
                    UserSection::firstOrCreate([
                        'section_id' => $location,
                        'user_id' => $user->id,
                    ]);
                }
                $success = true;
                $message = "User Created Successfully";
              }

            }else{
                $success = false;
                $message = "Password does not match!";
            }

            $response = [
                'success' => $success,
                'message' => $message,
        
            ];
    
          
            return response()->json($response);
    

    }


    public function logout()
    {
        try {
            Session::flush();
            $success = true;
            $message = "Logout successfully";
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }
}
