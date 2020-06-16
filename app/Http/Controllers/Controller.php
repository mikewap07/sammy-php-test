<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Users extends Controller
{
    public function index() {
    	$json = file_get_contents('php://input');
        if(sizeof($_POST) or $json or (isset($argv[1]) and isset($argv[2]))){
        	if($json){
		        if(!is_json($json))
		            return response('Invalid JSON', 422)->header('Content-Type', 'text/plain');
		        $_POST = json_decode($json, true);
		    }

    		if(strtoupper($_POST['password']) != '720DF6C2482218518FA20FDC52D4DED7ECC043AB')
    			return response('Invalid Password', 401)->header('Content-Type', 'text/plain');

    		if(!is_numeric($_POST['id']))
    			return response('Invalid ID', 422)->header('Content-Type', 'text/plain');

	    	$users = User::where('id', $_POST['id'])->get();
	    	if (count($users) <= 0) {
	    		return response('No such user (3)', 404)->header('Content-Type', 'text/plain');
	    	}else{
	    		$comment = $users[0]->comments ."\n".$_POST['comments'];

	            User::where('id', $_POST['id'])->update(array('comments' => $comment));
	    		return response('OK', 200)->header('Content-Type', 'text/plain');
	    	}
        } else if(isset($_GET['id'])){
        	$users = User::where('id', $_GET['id'])->get();
        	if (count($users) <= 0) {
        		return response('No such user (3)', 404)
    		         ->header('Content-Type', 'text/plain');;
    		}else{
    			return view('user',['users'=>$users]);
    		}
        }
    }

    function missing_post($field){
    	return (!isset($_POST[$field]) or !$_POST[$field]);
    }

    function missing_get($field){
    	return (!isset($_GET[$field]) or !$_GET[$field]);
    }

    function is_json($string) {
    	json_decode($string);
    	return (json_last_error() == JSON_ERROR_NONE);
    }
    
    public function showUser($id) {

    	/*$response = Http::post('http://127.0.0.1:8000/users', [
        	'id' => '1',
        	'password' => '720DF6C2482218518FA20FDC52D4DED7ECC043AB',
        	'comments' => 'BEST',
        ]);

        echo $response;*/

    	$users = User::where('id', $id)->get();
    	if (count($users) <= 0) {
    		return response('No such user (3)', 404)
    		         ->header('Content-Type', 'text/plain');;
    	}else{
    		return view('user',['users'=>$users]);
    	}
    }
    
    public function updateUser() {
    	if(strtoupper($_POST['password']) != '720DF6C2482218518FA20FDC52D4DED7ECC043AB')
    		return response('Invalid Password', 401)->header('Content-Type', 'text/plain');

    	if(!is_numeric($_POST['id']))
    		return response('Invalid ID', 422)->header('Content-Type', 'text/plain');

    	$users = User::where('id', $_POST['id'])->get();
    	if (count($users) <= 0) {
    		return response('No such user (3)', 404)->header('Content-Type', 'text/plain');
    	}else{
    		$comment = $users[0]->comments ."\n".$_POST['comments'];

            User::where('id', $_POST['id'])->update(array('comments' => $comment));
    		return response('OK', 200)->header('Content-Type', 'text/plain');
    	}
    }
}
