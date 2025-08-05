<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Enums\StatusCodeEnum;
use App\Exceptions\UnprocessableEntityException;
use App\Http\Controllers\Api\v1\Core\CoreController;
use App\Models\Uam\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Auth
 *
 * APIs for authentication (login, logout, token refresh, user info)
 */
final class AuthController extends CoreController
{
    /**
     * Login
     */
    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            /**
             * Email.
             * @var email
             * @example sa@app.com
             */
            'email' => 'required|email',
            /**
             * Password.
             * @var password
             * @example password
             */
            'password' => 'required',
        ]);

        // Check credentials
        if (! Auth::attempt($request->only('email', 'password'))) {
            throw new UnprocessableEntityException('Credential does not match');
        }

        // Get authenticated user
        $user = Auth::user();

        // Delete old tokens
        $user->tokens()->delete();

        // Create new access & refresh tokens
        $accessToken = $user->createToken('access_token', ['*'], now()->addMinutes(15))->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addDays(7))->plainTextToken;

        return $this->successResponse([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'full_name' => $user->name,
                'email' => $user->email,
                'image' => null,
                'roles' => ['superadmin', 'admin'],
                'permissions' => [
                    'read:dashboard',
                    // 'read:product',
                    // 'read:account',
                    // 'read:profile',
                    // 'read:login',
                    'read:uam',
                    'read:users',
                    'create:users',
                    // 'edit:users',
                    // 'read:roles',
                    // 'read:permissions',
                    // 'read:kanban',
                ]
            ],
        ]);
    }

    /**
     * Refresh token
     */
    public function refreshToken(Request $request)
    {
        try {
            $request->validate([
                'refresh_token' => 'required',
            ]);

            // Find user by refresh token
            $user = User::whereHas('tokens', function ($query) use ($request) {
                $query->where('token', hash('sha256', $request->refresh_token))
                    ->where('name', 'refresh_token');
            })->first();

            if (! $user) {
                return $this->errorResponse(['error' => 'User is not found'], StatusCodeEnum::UNPROCESSABLE_ENTITY);
            }

            // Delete old access tokens
            $user->tokens()->where('name', 'access_token')->delete();

            // Create new access token
            $newAccessToken = $user->createToken('access_token', ['*'], now()->addMinutes(15))->plainTextToken;

            return $this->successResponse([
                'access_token' => $newAccessToken,
                'token_type' => 'Bearer',
            ]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Refresh token failed');
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return $this->successResponse('Logged out successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Logout failed', ['error' => $e->getMessage()], StatusCodeEnum::UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Get user
     */
    public function user(Request $request)
    {
        try {
            return $this->successResponse('User fetched successfully', $request->user());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to fetch user data');
        }
    }
}
