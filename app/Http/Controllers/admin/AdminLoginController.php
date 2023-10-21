<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function index()
    {

        return view("admin.login");
    }
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required |email',
            'password' => 'required',
        ]);
        // if($validated)

        if (Auth::guard("admin")->attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::guard("admin")->user();
            // dd($user->role);
            if ($user->role == 2) {
                return to_route("admin.dashboard");
            } else {
                Auth::guard("admin")->logout();
                return to_route("admin.login")->with("error", "You are not authorized to accesses !");

            }

        } else {
            return to_route("admin.login")->with("error", "Either Email or password is incorrect");
        }
    }
    public function logout(Request $request)    {
        Auth::guard("admin")->logout();
        return to_route("admin.login")->with("success","You are logout successfully");
    }
}
