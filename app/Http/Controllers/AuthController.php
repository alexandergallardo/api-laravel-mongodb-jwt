<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     *
     * @OA\Post (
     *     path="/api/auth/auth",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="Get Token",
     *     description="Get Token with username and password",
     *     @OA\RequestBody(
     *         description="Get Token with username and password",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="username",
     *                      type="string",
     *                      description="Username"
     *                 ),
     *                 @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="Password"
     *                 ),
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    PUBLIC function login(Request $request)
    {
        if (! $token = auth()->attempt(["username"   => $request->username,
                                        "password"   => $request->password])) {
            return response()->json([
                "meta" => [
                    "success" => false,
                    "errors" => ['Password incorrect for :'.$request->username]
                ]
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            "meta" => [
                "success" => true,
                "error" => []
            ],
            "data"=> [
                "token" => $token,
                //"token_type" => 'bearer',
                "minutes_to_Expire" => auth()->factory()->getTTL() * 24
            ]
        ]);
    }

    /*
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:6|unique:users',
            'role' => 'required',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $validator->validate();

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['is_active']  = FALSE;
        $campos['last_login'] = null;

        $user = User::create($campos);

        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user
        ], 201);
    }*/
}
