<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;

class AuthController extends Controller
{
    /* Login handler for mobile app*/
    public function getUser(Request $request)
    {
        $email      = $request->email;
        $password   = $request->password;
        if (Auth::attempt(['email' => $email, 'password' => $password]))
        {
            $get_user = Auth::user();
            $token = Hash::make($password);
            $get_user->api_token = $token;
            $get_user->save();

            //return $token;
            //$accessToken =  $get_user->createToken('authToken')->accessToken;     
          return response()->json([
                'token' => $token,
                'status' => 'success',
                'message' => $get_user
          ],200);
        }
        else{
            return response()->json([
                'status' => 'error',
                'message'=>'Username or Password incorrect'
            ],403);
        }

    }
}
