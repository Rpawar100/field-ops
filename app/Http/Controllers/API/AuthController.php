<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user (Public)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'mobile' => 'required|string|unique:users,mobile',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,manager,fa,distributor',
            ]);

            // Create user (implementation depends on User model)
            // $user = User::create($validated);
            // $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    // 'user' => $user,
                    // 'token' => $token,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Login user with mobile + password or OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'mobile' => 'required|string',
                'password' => 'nullable|string',
                'otp' => 'nullable|string',
            ]);

            // Handle both password and OTP authentication
            // $user = User::where('mobile', $validated['mobile'])->first();

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    // 'user' => $user,
                    // 'token' => $token,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh authentication token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        try {
            // $newToken = $request->user()->createToken('auth_token')->plainTextToken;
            // $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    // 'token' => $newToken,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            // $user = $request->user();

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    // 'user' => $user,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile retrieval failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset user password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordReset(Request $request)
    {
        try {
            $validated = $request->validate([
                'mobile' => 'required|string',
                'otp' => 'required|string',
                'new_password' => 'required|string|min:8',
            ]);

            // Verify OTP and reset password
            // $user = User::where('mobile', $validated['mobile'])->first();

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
