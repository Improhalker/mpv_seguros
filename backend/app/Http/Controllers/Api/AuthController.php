<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $authentication = $this->authService->register($request->validated());

        return response()->json($this->authenticationPayload($authentication['user'], $authentication['token']), 201);
    }

    public function login(LoginRequest $request)
    {
        $authentication = $this->authService->login($request->validated());

        return response()->json($this->authenticationPayload($authentication['user'], $authentication['token']));
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $this->authService->logout($user);

        return response()->noContent();
    }

    public function me(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'user' => $this->userPayload($user->load('organization')),
        ]);
    }

    /**
     * @return array{token: string, token_type: string, user: array{id: int, name: string, email: string, is_active: bool, organization: array{id: int, name: string, timezone: string}}}
     */
    private function authenticationPayload(User $user, string $token): array
    {
        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->userPayload($user),
        ];
    }

    /**
     * @return array{id: int, name: string, email: string, is_active: bool, organization: array{id: int, name: string, timezone: string}}
     */
    private function userPayload(User $user): array
    {
        $organization = $user->organization;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'timezone' => $organization->timezone,
            ],
        ];
    }
}
