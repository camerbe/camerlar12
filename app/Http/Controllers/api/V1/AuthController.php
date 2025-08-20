<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //
    public function middleware():array {
        return [
            new Middleware('auth:sanctum', except: ['login']),
        ];
    }
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json(
            [
                'token' => $token,

            ]);
    }
    public function login(Request $request){
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        // Attempt authentication directly
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'user'    => null,
                'token'   => null,
                'message' => 'Email ou mot de passe incorrect',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::guard('api')->user();

        // Check password update
        if (is_null($user->password_changed_at)) {
            return response()->json([
                'success' => false,
                'user'    => new UserResource($user),
                'token'   => null,
                'message' => 'change_password',
            ], Response::HTTP_ACCEPTED);
        }

        // Check email verification
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'success' => false,
                'user'    => new UserResource($user),
                'token'   => null,
                'message' => 'verify_mail',
            ], Response::HTTP_ACCEPTED);
        }

        // ✅ Success
        return response()->json([
            'success' => true,
            'user'    => new UserResource($user) ,
            'token'   => $token,
            'message' => 'Login OK',
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        //dd($request->user()->currentAccessToken()->delete());
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "success"=>true,
            "message"=>"Logout ok"
        ],Response::HTTP_OK);


    }
}
