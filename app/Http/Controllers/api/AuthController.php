<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;

class AuthController extends Controller
{
    public function Register(StoreUserRequest $request)
    {
        $validated=$request->validated();
        $user = new User();
        $user->first_name=$validated['first_name'];
        $user->last_name=$validated['last_name'];
        $user->email=$validated['email'];
        $user->password=Hash::make($validated['password']);
        $user->save();
        Auth::login($user);
        $token = $user->createToken("access_token");
        return response($token, 200);
    }

    public function Login(LoginUserRequest $request)
    {
        $validated=$request->validated();
        $user = User::where('email', $validated['email'])->first();
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']]) || $user==null)
        {
            return response('Invalid login credentials', 401);
        }
        Auth::login($user);
        $token = $user->createToken("access_token");
        return response($token, 200);
    }

    public function getUser(Request $request) {
        return $request->user();
    }
}
