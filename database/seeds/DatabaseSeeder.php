<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role comes before User seeder here.
		$this->call(RolesTableSeeder::class);
		// User seeder will use the roles above created.
        $this->call(UsersTableSeeder::class);
    }
}
