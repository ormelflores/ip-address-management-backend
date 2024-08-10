<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthenticateUserRequest;
use App\Models\User;
use App\Observers\UserActivityObserver;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Login user and create access token
    public function store(AuthenticateUserRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials))
        {
            return $this->failedLoginAttemptResponse();
        }

        $user = Auth::user();

        $token = $user->createToken('authToken')->plainTextToken;

        (new UserActivityObserver)->login();

        return response()->json([
            'message' => 'Login successful.',
            'data' => $this->loginSuccessfulResponse($user, $token),
        ], 201);
    }

    // Logout user and revoke current access token
    public function logout(): JsonResponse
    {
        if (Auth::check())
        {
            (new UserActivityObserver)->logout();

            Auth::user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logout successful.',
        ], 201);
    }

    // Failed login attempt response
    protected function failedLoginAttemptResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Invalid login credentials.',
            'errors' => [
                'details' => 'These credentials do not match our records.',
            ],
        ], 422);
    }
    
    // Successful response
    protected function loginSuccessfulResponse(User $user, string $token): array
    {
        return [
            'user' => [
                'email' => $user->email,
                'name' => $user->name,
            ],
            'accessToken' => $token,
        ];
    }
}
