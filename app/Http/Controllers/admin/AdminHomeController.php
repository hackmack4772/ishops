<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    //

    public function index(){
        $user=Auth::guard("admin")->user();
        return "Welcome  ". $user->name ."<a href='logout'>Logout</a>";
    }

}
