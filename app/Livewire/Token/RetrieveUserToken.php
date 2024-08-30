<?php

namespace App\Livewire\Token;

use App\Services\TokenService;
use Livewire\Component;

class RetrieveUserToken extends Component
{
    public $latestToken;

    public $tokenName;

    public $newToken;

    public $showModal = false;

    protected $tokenService;

    protected $rules = [
        'tokenName' => 'required|string|min:3',
    ];

    public function boot(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function createToken()
    {
        $this->validate();

        $user = auth()->user();
        $this->newToken = $this->tokenService->createUserToken($user, $this->tokenName);

        $this->reset('tokenName');
        $this->showModal = false;
    }

    public function retrieveToken()
    {
        $user = auth()->user();

        return $this->tokenService->getLatestUserToken($user);
    }

    public function downloadPostmanCollection()
    {
        return response()->download(base_path('REST-API-COLLECTION.postman_collection.json'));
    }

    public function render()
    {
        return view('livewire.token.retrieve-user-token', [
            'token' => $this->retrieveToken(),
        ]);
    }
}
