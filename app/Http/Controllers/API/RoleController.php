<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function Rindex()
    {
        $roles = Role::all()->toArray();
        return array_reverse($roles);
    }

    public function Radd(Request $request)
    {
        $request->validate([
            'role_name' => 'required','unique',
            'description' => 'required',
            
        ]);


        $role = new Role();
        if( $request->description == 'user' || $request->description == 'User') {
            $role->id = 2;
        }
        $role->role_name = $request->role_name;
        $role->description = $request->description;      
         $role->save();
         return response()->json(['success'=> 'Post created successfully']);
    }



    public function Redit($id)
    {
        $post = Role::find($id);
        return response()->json($post);
    }


    public function Rupdate($id, Request $request)
    {


        $role = Role::find($id);
        $role->role_name = $request->name;
        $role->description = $request->description;
        $role->update();

        $success = true;
        $message = "Successfully Updated";
        $status = 200;

    $response = [
        'success' => $success,
        'message' => $message,
        'status' => $status
    ];
    return response()->json($response);

    }




    public function Rdelete($id)
    {
        $user = Role::find($id);
        $user->delete();
        return response()->json(['success'=> 'User deleted successfully']);

    }

   
}
