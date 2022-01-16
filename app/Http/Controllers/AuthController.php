<?php

namespace App\Http\Controllers;

use App\Models\Shared\LoginCode;
use App\Models\Shared\User;

class AuthController extends Controller
{
    public function sendCode()
    {
        try {
            $mobile = request('mobile');
            if (!checkMobileNumber($mobile)) {
                return response()->json(['message' => 'The entered mobile is not valid'], 419);
            }
            $user = User::findByMobile($mobile);
            if (!$user) {
                $user = User::create(['role_id' => '3', 'mobile' => $mobile, 'password' => 'pass-' . $mobile . 'word']);
            }
            $code = random_int(100000, 999999);
            LoginCode::createByUser($user->id, $code);
            $message = [
                'name' => $user->name,
                'code' => $code,
                'date' => jdate()->format('Y/m/d - H:i')
            ];
            sendSmsPattern('r112ol0e7w', $mobile, $message);
            return response()->json(null, 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function login()
    {
        try {
            LoginCode::deleteExpired();
            $user = User::findByMobileOrFail(request('mobile'));
            if (!$user->loginCode) {
                return response()->json(['message' => 'The entered code has expired'], 403);
            }
            if ($user->loginCode->code === request('code')) {
                if (!$token = auth()->login($user, true)) {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
                $message = [
                    'name' => $user->name,
                    'date' => jdate()->format('Y/m/d - H:i')
                ];
                sendSmsPattern('by505dy92e', $user->mobile, $message);
                return $this->respondWithToken($token);
            }
            return response()->json(['message' => 'The entered code is not valid'], 401);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user()
    {
        return response()->json(auth()->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}