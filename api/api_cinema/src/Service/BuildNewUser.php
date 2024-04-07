<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BuildNewUser
{
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this -> userPasswordHasher = $userPasswordHasher;
    }

    public function execute(array $parameters):User
    {
        $newUser = new User();
        $newUser -> setEmail($parameters["email"]);
        $hashedPassword = $this -> userPasswordHasher -> hashPassword($newUser, $parameters["plainPassword"]);
        $newUser -> setPassword($hashedPassword);
        $newUser -> setRoles(['ROLE_USER']);
        return $newUser;
    }
}