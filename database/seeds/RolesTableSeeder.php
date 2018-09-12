<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = new Role();
	    $adminRole->name = 'admin';
	    $adminRole->label = 'Super Admin';
	    $adminRole->save();

	    $prepaidRole = new Role();
	    $prepaidRole->name = 'prepaid';
	    $prepaidRole->label = 'Prepaid User';
	    $prepaidRole->save();

	    $postpaidRole = new Role();
	    $postpaidRole->name = 'postpaid';
	    $postpaidRole->label = 'Postpaid User';
	    $postpaidRole->save();

	    $managerRole = new Role();
	    $managerRole->name = 'manager';
	    $managerRole->label = 'Manager';
	    $managerRole->save();
    }
}
