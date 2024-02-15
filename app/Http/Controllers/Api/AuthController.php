<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
   

    public function signup(SignUpRequest $request)
    {
        $data = $request->validated();
        /** @var \App\Models\User $user */
       $user = User::create([
           'name'=>$data['name'],
           'email'=>$data['email'],
           'password'=>Hash::make($data['password']),
   
       ]);
        $token = $user->createToken('main')->plainTextToken;
         return response(compact('user','token'));   
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if(!Auth::attempt($credentials))
         {
            return response([
                'message'=> 'provided email address or password is incorrect'
            ],422);
         }
         /** @var \App\Models\User $user */
         $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response(compact('user','token'));   


    }

    public function logout(Request $request)
    {
        /** @var User $user*/
        $user =$request->user();
        $user->currentAccessToken()->delete();
        return response('',204);
    }
}
