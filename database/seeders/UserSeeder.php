<?php

namespace Database\Seeders;

use App\Domains\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1000)->create();

        if (! User::query()->where('is_admin', true)->first(['id'])) {
            User::factory()->makeAdmin()->count(25)->create();
        }

        User::firstOrCreate(
            ['email' => 'testadmin@mail.com',],
            [
                'name' => 'Test Status',
                'password' => Hash::make('123Admin*'),
                'is_admin' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'testuser@mail.com'],
            [
                'name' => 'Test Status',
                'password' => Hash::make('123User*'),
            ]
        );
    }
}
