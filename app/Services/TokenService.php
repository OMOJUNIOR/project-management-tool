<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserToken;

class TokenService
{
    /**
     * Get the latest token string for a given user.
     *
     * @return string|null
     */
    public function getLatestUserToken(User $user)
    {
        $latestToken = $user->userTokens()->latest()->first();

        if ($latestToken) {
            return $latestToken->token;
        }

        return null;
    }

    /**
     * Create a new token for the given user.
     *
     * @return string
     */
    public function createUserToken(User $user, string $tokenName, array $abilities = ['*'])
    {

        $user->userTokens()->delete();

        $token = $user->createToken($tokenName, $abilities);

        $userToken = UserToken::create([
            'user_id' => $user->id,
            'name' => $tokenName,
            'token' => $token->plainTextToken,
        ]);

        return $userToken->token;
    }
}
