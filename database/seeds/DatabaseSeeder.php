<?php

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BackOfficeAdminSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(ExpesesTypeSeeder::class);
        $this->call(CategorySeeder::class);
    }
}
