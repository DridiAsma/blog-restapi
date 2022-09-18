<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //Register Function User
    public function register(Request $request)
    {
        //Validation fields
        $valider = $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed'
            ]);

            //create user
            $user = User::create([
                'name' => $valider['name'],
                'email' => $valider['email'],
                'password' => bcrypt($valider['password'])
            ]);

            //return user & token in response
            return response([
                'user' => $user,
                'token' => $user->createToken('secret')->plainTextToken
            ], 200);



    }
}
