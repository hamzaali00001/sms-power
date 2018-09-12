<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = new User();
        $adminUser->role_id = 1;
        $adminUser->name = 'Steve Gitau';
        $adminUser->email = 'stevegitau@gmail.com';
        $adminUser->email_verified_at = date('Y-m-d H:i:s');
        $adminUser->password = bcrypt('123456');
        $adminUser->mobile = '0722225591';
        $adminUser->credit = 100000;
        $adminUser->timezone = 'Africa/Nairobi';
        $adminUser->save();

        $prepaidUser = new User();
        $prepaidUser->role_id = 2;
        $prepaidUser->name = 'Steve Mikes';
        $prepaidUser->email = 'prepaid@gmail.com';
        $prepaidUser->email_verified_at = date('Y-m-d H:i:s');
        $prepaidUser->password = bcrypt('123456');
        $prepaidUser->mobile = '0700112233';
        $prepaidUser->credit = 50000;
        $prepaidUser->timezone = 'Africa/Nairobi';
        $prepaidUser->save();

        $postpaidUser = new User();
        $postpaidUser->role_id = 3;
        $postpaidUser->name = 'Kenya Power';
        $postpaidUser->email = 'postpaid@gmail.com';
        $postpaidUser->email_verified_at = date('Y-m-d H:i:s');
        $postpaidUser->password = bcrypt('123456');
        $postpaidUser->mobile = '0712345678';
        $postpaidUser->credit = 50000;
        $postpaidUser->timezone = 'Africa/Nairobi';
        $postpaidUser->save();
		
		$managerUser = new User();
        $managerUser->parent_id = 1;
        $managerUser->role_id = 4;
        $managerUser->name = 'Bernard Kitur';
        $managerUser->email = 'bkitur@gmail.com';
        $managerUser->email_verified_at = date('Y-m-d H:i:s');
        $managerUser->password = bcrypt('123456');
        $managerUser->mobile = '0723540877';
        $managerUser->credit = 100000;
        $managerUser->timezone = 'Africa/Nairobi';
        $managerUser->save();
    }
}
