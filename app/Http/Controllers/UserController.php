<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return new UserCollection(User::all());
    }

    public function show($id)
    {
        return new UserResource(User::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $User = User::create($request->all());

        return (new UserResource($User))
            ->response()
            ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $request->merge(['correct' => (bool) json_decode($request->get('correct'))]);
        $request->validate([
            'correct' => 'required|boolean'
        ]);

        $User = User::findOrFail($id);
        $User->answers++;
        $User->points = ($request->get('correct')
            ? $User->points + 1
            : $User->points - 1);
        $User->save();

        return new UserResource($User);
    }

    public function delete($id)
    {
        $User = User::findOrFail($id);
        $User->delete();

        return response()->json(null, 204);
    }


}
