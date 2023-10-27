<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    //

    public function index(){
     return view("admin.dashboard");
    }

}
