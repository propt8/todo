<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiException;
use App\Exceptions\Error;
use App\Http\Controllers\Controller;
use App\Http\Response\FractalResponse;
use App\Transformers\AuthTransformer;
use App\Transformers\StatusResponseTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     * @param FractalResponse $fractal
     */
    public function __construct(
        FractalResponse $fractal
    )
    {
        parent::__construct($fractal);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     description="Login to User",
     *   @OA\Parameter(
     *     name="email",
     *     required=true,
     *     in="query",
     *     description="The email for login",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The password for login in clear text",
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Return the token information.",
     *         @OA\JsonContent(ref="#/components/schemas/Auth")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     *
     * @throws ApiException
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            throw new ApiException(Error::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    /**
     *
     * Get auth user info
     *
     * @OA\Get(
     *     path="/auth/me",
     *     operationId="me",
     *     tags={"Auth"},
     *     description="Auth user info",
     *     @OA\Response(
     *         response="200",
     *         description="Return the user information.",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     */
    public function me()
    {
        return $this->item(auth()->user(), new UserTransformer());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return array
     *
     * @OA\Post(
     *     path="/auth/logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *     description="Logout",
     *     @OA\Response(
     *         response="200",
     *         description="Return the status.",
     *         @OA\JsonContent(ref="#/components/schemas/Status")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()
            ->json($this->item(true, new StatusResponseTransformer()));
    }

    /**
     *  Refresh a token
     *
     * @return array
     *
     * @OA\Post(
     *     path="/auth/refresh",
     *     operationId="refresh",
     *     tags={"Auth"},
     *     description="Refresh token",
     *     @OA\Response(
     *         response="200",
     *         description="Return the token information.",
     *         @OA\JsonContent(ref="#/components/schemas/Auth")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return $this->item(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ], new AuthTransformer()
        );
    }
}
