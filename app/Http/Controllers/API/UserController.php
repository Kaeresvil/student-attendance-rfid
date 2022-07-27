<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\RelationNotFoundException;
// use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function Uindex()
    {
        $users = User::with('role')->get();
        return response()->json($users);
 
    }


    public function Udelete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['success'=> 'User deleted successfully']);

    }


    public function Uedit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }


    public function Uupdate( Request $request , $id)
    {


            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_type_id = $request->role_type;


         if($request->role_type != null){
            $user->update();

            $success = true;
            $message = "User updated successfully";
         }else{
            $success = false;
            $message = "User role required!";
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
            'role_type' => ['required'],
            'confirmpassword' => ['required', 'string', 'min:8'],
        ]);
       

            $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role_type_id = $request->role_type;
                $user->password = Hash::make($request->password);
         
                  if($request->password == $request->confirmpassword){

                 
              if( $user->save()){
                $success = true;
                $message = "User login successfully";
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
