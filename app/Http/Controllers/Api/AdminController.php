<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class AdminController extends Controller
{
    public function listUser()
    {
    	$users = User::get();
    	return response()->json($users);
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
    	$user = User::find($id);
    	if (!$user) {
    		abort(404);
    	}
		$user->update($request->input());
		return response()->json($user);
    }

    public function destroyUser($id)
    {
    	$user = User::find($id);
        if (!$user) {
            abort(404);
        }
        if ($user->delete()) {
            return response()->json([
                'message' => 'User successfully deleted'
            ]);
        }
        return response()->json([
            'message' => 'Oops something went wrong. Please try again.'
        ], 500);
    }
}
