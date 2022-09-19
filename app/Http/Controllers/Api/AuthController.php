<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


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

     //login function
     public function login (Request $request)
     {
        //Validation fields
        $valider = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            // attempt login
            if(!Auth::attempt($valider))
            {
                return response([ 'message' => 'Invalid credentials.'], 403);
            }
            return response([
                'user' => $user = Auth::user(),
                'token' => $user->createToken('secret')->plainTextToken
            ], 200);

    }


    //logout user
    public function logout(User $user)
    {
        $user->tokens()->delete();
        return response([
            'message' => 'logout success.'
        ], 200);
    }


    // getUser Profile
    public function user(){
        return response([
         'user' => auth()->user()
        ], 200);
    }

    public function update(Request $request)
    {
        $valide = $request->validate([
            'name' => 'required|string'
        ]);
        $image = $this->saveImage($request->image, 'profiles');

        $user = Auth::user()->update([
            'name' => $valide['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'User update',
            'user' => $user
        ], 200);
    }
}

