<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Service initialize variable
     *
     */
    protected $service; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle logic to display a user list.
     *
     */
    public function listUser()
    {
    	$users = User::get();
    	return response()->json($users);
    }

    /**
     * Handle logic to update a user.
     *
     * @param $request
     * @param $id
     *
     * @return json
     */
    public function updateUser(UpdateUserRequest $request, $id)
    {
    	$user = User::find($id);
    	if (!$user) {
    		abort(404);
    	}
        $updateUser = $this->service->update($user, $request->input());
        if ($updateUser) {
            return response()->json($user);
        }
        return response()->json([
            'message' => 'Oops something went wrong. Please try again.'
        ], 500);
    }

    /**
     * Handle logic to delete a user.
     *
     * @param $id
     *
     * @return json
     */
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
