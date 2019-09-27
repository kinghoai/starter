<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function init()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3|max:255',
            'email'=>'required|unique:users,email|min:5|max:255',
            'password'=>'required|min:3',
        ]);
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $user = $this->create($data);
        Auth::login($user);
        return response()->json($user, 200);
    }

    public function login(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(Auth::user(), 200);
        } else {
            return response()->json(['error'=>'Could not login'], 401);
        }
    }

    public function logout() {
        Auth::logout();
    }
}
