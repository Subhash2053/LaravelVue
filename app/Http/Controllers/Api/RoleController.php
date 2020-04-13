<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){
        $data['roles'] = Role::all();
        $headers =[];
     return response()->json($data, 200, $headers);
    }


    public function store(Request $request){
        $name = $request->get('name');
        $role = new Role();
        $role->name = $name ;
        $role->save();

        return response()->json(['role'=>$role], 200);

      // dd($request);
    }

    public function update($id,Request $request){
        $name = $request->get('name');
        $role =  Role::find($id);
        $role->name = $name ;
        $role->save();

        return response()->json(['role'=>$role], 200);

        dd($request);
    }


    public function destroy($id)
    {
        $role = Role::find($id)->delete();
        return response()->json(['role'=>$role], 200);
    }
}
