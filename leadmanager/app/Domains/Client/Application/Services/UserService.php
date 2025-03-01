<?php

namespace App\Domains\User\Application\Services;
use App\Domains\User\Domain\Entities\User;

class UserService {


    protected $userRepository;

    public function __construct(?array $userRepository)
    {
        $this->userRepository = $userRepository;
    }

     public function register(array $data)
    {
        // $user = new User(null, $data['name'], $email, bcrypt($data['password']));
        // return $this->userRepository->save($user);
    }
}