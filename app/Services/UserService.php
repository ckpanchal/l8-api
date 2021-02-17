<?php

namespace App\Services;

use App\Repositories\UserRepository;

/**
 * User class to handle operator interactions.
 */
class UserService
{
    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new service instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle logic to create a user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($user, $data)
    {
        $user = $this->userRepository->create($user, $data);
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
        $user = $this->userRepository->update($user, $data);
        return $user;
    }

}
