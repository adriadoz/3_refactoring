<?php
declare(strict_types=1);

namespace LoginApp\Domain\Repository;

use LoginApp\Domain\Model\User\User;
use LoginApp\Domain\Model\ValueObject\Email;

interface UserRepositoryInterface
{
    public function registerNewUser(User $user);

    public function findUserByEmail(Email $email);
}