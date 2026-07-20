<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * @param  array{organization_name: string, name: string, email: string, password: string}  $attributes
     * @return array{user: User, token: string}
     */
    public function register(array $attributes): array
    {
        return DB::transaction(function () use ($attributes): array {
            $organization = Organization::query()->create([
                'name' => $attributes['organization_name'],
                'timezone' => 'America/Sao_Paulo',
            ]);

            $user = User::query()->create([
                'organization_id' => $organization->id,
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => Hash::make($attributes['password']),
                'is_active' => true,
            ]);

            $user->load('organization');

            return [
                'user' => $user,
                'token' => $user->createToken('auth')->plainTextToken,
            ];
        });
    }

    /**
     * @param  array{email: string, password: string}  $attributes
     * @return array{user: User, token: string}
     *
     * @throws ValidationException
     */
    public function login(array $attributes): array
    {
        $user = User::query()
            ->with('organization')
            ->where('email', $attributes['email'])
            ->first();

        if (! $user || ! $user->is_active || ! Hash::check($attributes['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais informadas são inválidas.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth')->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
