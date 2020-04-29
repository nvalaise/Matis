<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
	public function home() {
    	return view('app.home');
    }

    public function auth() {
    	return view('app.auth');
    }

    public function deezer() {
    	return view('app.deezer');
    }
    
    public function spotify() {
    	return view('app.spotify');
    }
}
