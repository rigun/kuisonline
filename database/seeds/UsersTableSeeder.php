<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin','user'];

        foreach($roles as $role){
            $this->command->info('Creating Role '. strtoupper($role));
            
            $r = new Role();
            $r->name = $role;
            $r->save();
            
            $this->command->info('Creating User '. strtoupper($role));
            $user = new User();
            $user->username = $role;
            $user->password = bcrypt('password');
            $user->role_id = $r->id;
            $user->save();
        }
    }
}
