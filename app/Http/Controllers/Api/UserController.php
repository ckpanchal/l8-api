<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Services\UserService;
use JWTAuth;

class UserController extends Controller
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
     * Handle logic to update a user.
     *
     * @param $request
     *
     * @return mixed
     */
    public function update(UpdateUserRequest $request) 
    {
    	$user = JWTAuth::user();
        $updateUser = $this->service->update($user, $request->input());
        if ($updateUser) {
            return response()->json($updateUser);
        } else {
            return response()->json([
                'message' => 'Oops something went wrong. Please try again.',
            ], 500);    
        }
    }

    /**
     * Handle logic to update a user password.
     *
     * @param $request
     *
     * @return mixed
     */
    public function changePassword(ChangePasswordRequest $request) 
    {
    	$user = JWTAuth::user();
        $updateUser = $user->update(array_merge(
                $request->input(),
                ['password' => bcrypt($request->password)]
            ));
        if ($updateUser) {
            return response()->json([
                'message' => 'Password updated successfully.'
            ]);
        } else {
            return response()->json([
                'message' => 'Oops something went wrong. Please try again.',
            ], 500);
        }
    }
}
