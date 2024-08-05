<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $users = User::with(['country', 'state', 'city'])->get();
        return view('frontend.index', compact('users'));
    }    
}
