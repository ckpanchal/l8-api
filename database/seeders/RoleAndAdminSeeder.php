<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = 'Admin,User';
        $rolesArray = explode(',', $roles);
        foreach ($rolesArray as $role) {
        	$roleObj = Role::Create([
                'guard_name' 	=> 'api',
                'name' 			=> trim($role)
            ]);
            if($roleObj && ($role == 'Admin')) {
            	$this->createAdmin($roleObj);
            }
        }
    }

    private function createAdmin($role)
    {
        $user = User::create([
            'username'      => 'superadmin',
            'email' 		=> 'admin@example.com',
            'password' 		=> Hash::make('secret'),
            'name' 			=> 'Super Admin',
            'contact_no' 	=> '1234567890',
            'is_approved'   => 1,
            'created_at' 	=> Carbon::now(),
        ]);
        $user->assignRole($role);
    }
}
