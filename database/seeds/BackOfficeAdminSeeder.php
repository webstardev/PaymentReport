<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class BackOfficeAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert(
            [
                'username'=> 'Admin',
                'password' => bcrypt('BackAdmin123!'),
                'user_type' => 'Admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

    }
}
