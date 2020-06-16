<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Users extends Controller
{
    public function index() {
    	$users = User::all();

    	return view('user',['users'=>$users]);
    }
    
    public function showUser($id) {
    	$users = User::where('id', $id)->get();
    	if (count($users) <= 0) {
    		return response('No such user (3)', 404);
    	}else{
    		return view('user',['users'=>$users]);
    	}
    }
}
