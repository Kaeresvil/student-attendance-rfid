<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function Rindex(Request $request)
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
            $query = Role::where('role_name', 'LIKE', '%'.$search.'%')->orderBy($orderByColumn, $direction)->paginate($limit);
        }else{
            $query = Role::orderBy($orderByColumn, $direction)->paginate($limit);
        }




        return response()->json([
            'message' => 'Successfully Created',
            'data' => $query
        ], 200);
    }

    public function getall(){
        $grade = Role::all()->toArray();
        return array_reverse($grade);
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
         return response()->json(['success'=> 'Role Created Successfully']);
    }



    public function Redit($id)
    {
        $post = Role::find($id);
        return response()->json($post);
    }


    public function Rupdate($id, Request $request)
    {
        $request->validate([
            'role_name' => 'required','unique',
            'description' => 'required',
            
        ]);


        $role = Role::find($id);
        $role->role_name = $request->role_name;
        $role->description = $request->description;
        $role->update();

        return response()->json(['success'=> 'Role Updated Successfully']);

    }




    public function Rdelete($id)
    {
        $user = Role::find($id);
        $user->delete();
        return response()->json(['success'=> 'Role Deleted Successfully']);

    }

   
}
