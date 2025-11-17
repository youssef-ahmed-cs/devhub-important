<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialiteMediaController
{
    public function login(): JsonResponse
    {
        $redirectUrl = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'url' => $redirectUrl
        ]);
    }

    public function callback(): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $username = $googleUser->getNickname()
            ?? explode('@', $googleUser->getEmail())[0]
            . '_' . substr($googleUser->getId(), 0, 5);

        $user = User::updateOrCreate(
            [
                'provider_id' => $googleUser->getId(),
            ],
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'username' => $username,
                'password' => bcrypt(str()->random(16)),
                'avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'bio' => $googleUser->getNickname() ?? '',
            ]
        );

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

}
