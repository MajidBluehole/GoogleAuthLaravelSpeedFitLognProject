<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    public function index()
    {
        return view('frontend.maps-google');
    }
}
