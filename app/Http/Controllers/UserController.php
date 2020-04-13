<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\User;
use App\Role;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->per_page ? $request->per_page : 5;
        $sortBy = $request->sort_by ? $request->sort_by : 'name';
        $orderBy = $request->order_by ? 'asc' : 'desc';
        return response()->json([
            'users' => new UserCollection(User::orderBy($sortBy, $orderBy)->paginate($per_page)),
            'roles' => Role::pluck('name')->all()
        ], 200);
       // return new UserCollection(User::all());
    }

    public function show($id)
    {
        $users = User::where('name', 'LIKE', "%$id%")->paginate();
        return response()->json(UserResource($users), 200);
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $role = isset($request->role) ? Role::where('name', $request->role)->first() : Role::where('name', 'Subscriber')->first();
        $user = new User([
            'name' =>$request->name,
            'email'=>$request->email,
            'password' => bcrypt($request->password),
        ]);
       // $user->role()->associate($role);
        $user->save();
       // $user->profile()->save(new Profile);
        return response()->json(['user'=> new UserResource($user)], 200);
    }

    public function update($id, Request $request)
    {
        $role = Role::where('name', $request->role)->first();
        $user = User::find($id);
        $user->name = $request->name;
        //$user->role()->dissociate($user->role);
       // $user->role()->associate($role);
        $user->save();
        return response()->json(['user'=> new UserResource($user)], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id)->delete();
        //Profile::where('user_id', $id)->delete();
        return  response()->json(['user'=>$user], 200);
    }

    public function deleteAll(Request $request)
    {
        User::whereIn('id', $request->users)->delete();
        return response()->json(['message', 'Records Deleted Successfully'], 200);
    }

    public function changeRole(Request $request)
    {
        $user = User::find($request->user);
        $logedInUser = $request->user();
        if ($user->id === $logedInUser->id) {
            return response()->json(['user'=> new UserResource($logedInUser)], 422);
        }
        $role = Role::where('name', $request->role)->first();
        //$user->role()->associate($role);
        $user->save();
        return response()->json(['user' => new UserResource($user)], 200);
    }
    public function changePhoto(Request $request)
    {
        $user = User::find($request->user);
        $profile = Profile::where('user_id', $request->user)->first();
        $ext = $request->photo->extension();
        $photo = $request->photo->storeAs('images', Str::random(20).".{$ext}", 'public');
        $profile->photo = $photo;
        //$user->profile()->save($profile);
        return response()->json(['user' => new UserResource($user)], 200);
    }
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users'
        ]);

        return response()->json(['message'=> 'Valid Email'], 200);
    }


}
