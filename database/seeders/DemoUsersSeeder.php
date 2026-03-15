<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Usuários demo para testes em desenvolvimento.
 * Senha padrão: password
 */
class DemoUsersSeeder extends Seeder
{
    public const EMAIL_ADMIN = 'admin@demo.vertex.local';
    public const EMAIL_PASTOR = 'pastor@demo.vertex.local';
    public const EMAIL_ALUNO = 'aluno@demo.vertex.local';
    public const PASSWORD = 'password';

    public function run(): void
    {
        $demos = [
            [
                'email' => self::EMAIL_ADMIN,
                'first_name' => 'Admin',
                'last_name' => 'Demo',
                'password' => self::PASSWORD,
                'email_verified_at' => now(),
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'email' => self::EMAIL_PASTOR,
                'first_name' => 'Pastor',
                'last_name' => 'Demo',
                'password' => self::PASSWORD,
                'email_verified_at' => now(),
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'email' => self::EMAIL_ALUNO,
                'first_name' => 'Aluno',
                'last_name' => 'Demo',
                'password' => self::PASSWORD,
                'email_verified_at' => now(),
                'is_active' => true,
                'status' => 'active',
            ],
        ];

        foreach ($demos as $data) {
            $password = $data['password'];
            $emailVerifiedAt = $data['email_verified_at'] ?? null;
            unset($data['password'], $data['email_verified_at']);
            $data['password'] = Hash::make($password);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
            if ($emailVerifiedAt !== null) {
                $user->email_verified_at = $emailVerifiedAt;
                $user->save();
            }
        }
    }
}
