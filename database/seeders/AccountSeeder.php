<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::factory()
            ->state(new Sequence(
                    ['type'=>0],
                    ['type'=>1],
                    ['type'=>2],
                    ['status'=>0],
                    ['status'=>1],
                    ['status'=>2],
                )
            )
            ->count(10)
            ->create();
    }
}
