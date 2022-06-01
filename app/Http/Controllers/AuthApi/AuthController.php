<?php

namespace App\Http\Controllers\AuthApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.jwt', ['except' => ['login', 'closeSession']]);
    }

    /**
     * login
     *
     * Method for authenticate user
     *
     * @bodyParam email email Email of the person of the registered. Example: prueba@gmail.com
     * @bodyParam password string Account password
     *
     * @response {
     *      "access_token": "token",
     *      "token_type": "bearer",
     *      "expires_in": 3600,
     *      "user": {
     *          "id": 1,
     *          "name": "Juan",
     *          "last_name": "Corrales",
     *          "identification": "6807138675089",
     *          "username": "jdcorrales",
     *          "email": "j@j.com",
     *          "deleted_at": null,
     *          "updated_at": "2019-09-22 00:36:03",
     *          "created_at": "2019-09-22 00:36:03"
     *      }
     * }
     *
     * @response 401{
     *     "message": "Unauthorized"
     * }
     *
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if($this->checkLogin($token)) {
            try {
                \JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($token), $forceForever = false);
            } catch (\Throwable $th) {
                throw $th;
            }

            return response()->json(['error' => 'Ya tiene una session iniciada, cierre la sessión e inicie de nuevo.'], 403);
        }

        return $this->respondWithToken($token);
    }

    /**
     * me
     *
     * Method for get uthenticate user
     *
     * @response {
     *     "id": 1,
     *     "name": "Juan",
     *     "last_name": "Corrales",
     *     "identification": "6807138675089",
     *     "username": "jdcorrales",
     *     "email": "jdcorrales@gmail.com",
     *     "deleted_at": null,
     *     "updated_at": "2019-09-22 00:36:03",
     *     "created_at": "2019-09-22 00:36:03"
     * }
     *
     * @response 401{
     *     "message": "Unauthorized"
     * }
     *
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }
    public function payload()
    {
        return response()->json(auth('api')->payload());
    }

    /**
     * logout
     *
     * Method for logout user
     *
     * @response {
     *     "message": "Successfully logged out"
     * }
     *
     * @response 401{
     *     "message": "Unauthorized"
     * }
     *
     */
    public function logout()
    {
        DB::connection('seguros')->table('sessions')
        ->where('user_id', auth('api')->user()->id)
        ->delete();

        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * refresh
     *
     * Method for update access token
     *
     * @response {
     *      "access_token": "token",
     *      "token_type": "bearer",
     *      "expires_in": 3600,
     *      "user": {
     *          "id": 1,
     *          "name": "Juan",
     *          "last_name": "Corrales",
     *          "identification": "6807138675089",
     *          "username": "jdcorrales",
     *          "email": "j@j.com",
     *          "deleted_at": null,
     *          "updated_at": "2019-09-22 00:36:03",
     *          "created_at": "2019-09-22 00:36:03"
     *      }
     * }
     *
     * @response 401{
     *     "message": "Unauthorized"
     * }
     *
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    protected function checkLogin($token)
    {
        $session = DB::connection('seguros')->table('sessions')
        ->where('user_id', auth('api')->user()->id)
        ->get([
            'id',
            DB::raw('TIMESTAMPADD(SECOND,expires_in,created_at) < NOW() AS expiration'),
        ]);

        $expiration = collect($session->where('expiration', 1)->pluck('id')->toArray());

        if ($expiration->count()) {
            DB::connection('seguros')->table('sessions')
            ->whereIn('id', $expiration->toArray())
            ->delete();
        }

        $session = $session->filter(function ($session, $key) {
            return (int) $session->expiration === 0;
        });

        if (!$session->count()) {
            DB::connection('seguros')->table('sessions')
            ->insert([
                'user_id' => auth('api')->user()->id,
                'token' => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        } else {
            return true;
        }

        return false;
    }

    public function closeSession(Request $request)
    {
        try {
            $user = DB::connection('seguros')->table('users')
            ->where('email', $request->email)
            ->first(['id']);

            $session = DB::connection('seguros')->table('sessions')
            ->where('user_id', $user->id)
            ->first();

            try {
                \JWTAuth::manager()
                ->invalidate(new \Tymon\JWTAuth\Token($session->token), $forceForever = false);
            } catch (\Throwable $th) {}


            $session = DB::connection('seguros')->table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 422);
        }

        return response()->json(['message' => 'se cerro la sesión satisfactoriamente.']);
    }
}
