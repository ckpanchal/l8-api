<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Role;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Register a new user",
     * security={{"bearer_token":{}}},
     * description="Register a new user",
     * operationId="register",
     * tags={"Auth"},
     * @OA\Parameter(
     *    description="User Name",
     *    in="query",
     *    name="name",
     *    required=true,
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="User Username (Unique)",
     *    in="query",
     *    name="username",
     *    required=true,
     *    @OA\Schema(
     *       type="string",
     *    )
     * ),
     * @OA\Parameter(
     *    description="User Email (Unique)",
     *    in="query",
     *    name="email",
     *    required=true,
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="Password",
     *    in="query",
     *    name="password",
     *    required=true,
     *    @OA\Schema(
     *       type="string",
     *    )
     * ),
     * @OA\Parameter(
     *    description="Confirm Password [Should Match With Password]",
     *    in="query",
     *    name="password_confirmation",
     *    required=true,
     *    @OA\Schema(
     *       type="string",
     *    )
     * ),
     * @OA\Parameter(
     *    description="User Contact Number",
     *    in="query",
     *    name="contact_no",
     *    required=true,
     *    example="9876543210",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )
     * ),
     * @OA\Response(
     *    response=201,
     *    description="User successfully registered",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User successfully registered")
     *        )
     *     )
     * )
     */
    public function register(RegisterRequest $request) 
    {
        $user = User::create(array_merge(
                    $request->input(),
                    ['password' => bcrypt($request->password)]
                ));
        $role = Role::findByName('User');
        if ($role) {
            $user->assignRole($role);
        }
        return response()->json([
            'message' => 'User successfully registered.',
            'user' => $user
        ], 201);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Login User",
     * security={{"bearer_token":{}}},
     * description="Login User",
     * operationId="login",
     * tags={"Auth"},
     * @OA\Parameter(
     *    description="Username",
     *    in="query",
     *    name="username",
     *    required=true,
     *    @OA\Schema(
     *       type="string",
     *    )
     * ),
     * @OA\Parameter(
     *    description="Password",
     *    in="query",
     *    name="password",
     *    required=true,
     *    @OA\Schema(
     *       type="string",
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthorized")
     *        )
     *     )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Response of authentication token",
     *    @OA\JsonContent(
     *       @OA\Property(property="access_token", type="string"),
     *       @OA\Property(property="token_type", type="string"),
     *       @OA\Property(property="expires_in", type="integer"),
     *       @OA\Property(property="user", type="string"),
     *        )
     *     )
     * ),
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->generateToken($token);
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Logout User",
     * security={{"bearer_token":{}}},
     * description="Logout User",
     * operationId="logout",
     * tags={"Auth"},
     * @OA\Response(
     *    response=200,
     *    description="User logout successfully.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User logout successfully.")
     *        )
     *     )
     * )
     */
    public function logout() 
    {
        auth()->logout();
        return response()->json(['message' => 'User logout successfully.']);
    }

    /**
     * @OA\Post(
     * path="/api/token-refresh",
     * summary="Token Refresh",
     * security={{"bearer_token":{}}},
     * description="Token Refresh",
     * operationId="refresh",
     * tags={"Auth"},
     * @OA\Response(
     *    response=200,
     *    description="Refresh token data",
     *    @OA\JsonContent(
     *       @OA\Property(property="access_token", type="string"),
     *       @OA\Property(property="token_type", type="string"),
     *       @OA\Property(property="expires_in", type="integer"),
     *       @OA\Property(property="user", type="string"),
     *        )
     *     )
     * )
     */
    public function refresh() 
    {
        return $this->generateToken(auth()->refresh());
    }

    /**
     * @OA\Get(
     * path="/api/user",
     * summary="Get Auth User",
     * security={{"bearer_token":{}}},
     * description="Get Auth User",
     * operationId="user",
     * tags={"Auth"},
     * @OA\Response(
     *    response=200,
     *    description="Response of login user"
     *     )
     * )
     */
    public function user() 
    {
        return response()->json(JWTAuth::user());
    }

    protected function generateToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => JWTAuth::user()
        ]);
    }
}
