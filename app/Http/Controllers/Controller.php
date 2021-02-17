<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="My First API",
 *    version="1.0.0",
 *    description="API to interact with Project",
 *    contact={
 *       "name": "API Support",
 *       "url": "https://www.example.com/",
 *       "email": "info@example.com"
 *       }
 * )
 * @OA\SecurityScheme(
 * securityScheme="bearer_token",
 * description="Enter your API Key",
 * type="http",
 * scheme="bearer"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
