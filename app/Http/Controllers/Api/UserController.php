<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use JWTAuth;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request) 
    {
    	try {
    		$user = JWTAuth::user();
    		$user->update($request->input());
	    	return response()->json($user);
    	} catch(Exception $e) {
    		return response()->json([
    			'message' => $e->getMessage(),
    		], $e->getCode());
    	}
    }

    public function changePassword(ChangePasswordRequest $request) 
    {
    	try {
    		$user = JWTAuth::user();
    		$user->update(array_merge(
                    $request->input(),
                    ['password' => bcrypt($request->password)]
                ));
	    	return response()->json([
	    		'message' => 'Password updated successfully.'
	    	]);
    	} catch(Exception $e) {
    		return response()->json([
    			'message' => $e->getMessage(),
    		], $e->getCode());
    	}
    }
}
