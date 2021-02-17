<?php

namespace App\Repositories;

use App\Models\User;
use DB;

/**
 * Repository class for User model.
 */
class UserRepository extends BaseRepository
{
    /**
     * Handle logic to create a new user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create(User $user, $data)
    {
        $user = User::create([
            'name'          => $data['name'],
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password'      => $data['password'],
            'contact_no'    => $data['contact_no']
        ]);

        $role = Role::findByName('User');

        $user->syncRoles($role);

        return $user;
    }

    /**
     * Handle logic to update a user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $data)
    {
        $user->fill([
            'name'          => $data['name'],
            'username'      => $data['username'],
            'email'         => $data['email'],
            'contact_no'    => $data['contact_no']
        ]);
        
        $user->save();

        return $user;
    }
}
