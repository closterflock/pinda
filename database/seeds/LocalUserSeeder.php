<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class LocalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $user */
        $user = factory(User::class)
            ->states('api')
            ->create([
                'email' => 'test@pinda.test'
            ]);
        $this->command->info('Successfully seeded auth user.');
        $this->command->info('Email address is: ' . $user->email);
        $this->command->info('Password is: secret');
        $this->command->info('Auth token is: ' . $user->getAuthToken()->token);
    }
}
