<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        
        $user->name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('lolzibar');
        $user->address = 'Kerkstraat 1';
        $user->town = 'Antwerp';
        $user->ipaddress = '127.0.0.1';
        $user->isAdmin = 1;
        
        $user->save();
    }
}
