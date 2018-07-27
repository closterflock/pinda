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
        $this->command->info('Auth token is: ' . $user->getAuthToken()->token);
        //TODO create test user
        //TODO create with email test@pinda.test
        //TODO create with password 'secret'
        //TODO create with auth token for API access
        //TODO use factory with state to generate auth token?
    }
}
