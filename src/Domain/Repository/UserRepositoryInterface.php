<?php
declare(strict_types=1);

namespace Domain\Repository;

interface UserRepositoryInterface
{
    public function registerNewUser(User $user);

    public function findUserByEmail(Email $email);
}