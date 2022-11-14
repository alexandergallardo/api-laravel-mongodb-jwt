<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description",
 *      x={
 *          "logo": {
 *              "url": "https://via.placeholder.com/190x90.png?text=L5-Swagger"
 *          }
 *      },
 *      @OA\Contact(
 *          email="alexander.gallardo@itpeoplesas.net"
 *      ),
 *      @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Server(
 *     description="Local Dev Server",
 *     url="http://localhost:8000"
 * )
 *
 *  * Base level security. Overridable at controller level
 * @OA\OpenApi(
 *   security={ { "bearer":{} } }
 * )
 *
 * @OA\SecurityScheme(
 *      type="http",
 *		description="Login with username and password to get the authentication token",
 *      in="header",
 *      scheme="bearer",
 *      securityScheme="bearer"
 * )
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
