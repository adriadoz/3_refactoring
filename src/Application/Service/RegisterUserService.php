<?php
declare(strict_types=1);

namespace LoginApp\Application\Service;

use LoginApp\Domain\Repository\UserRepositoryInterface;
use LoginApp\Domain\Model\User\User;
use LoginApp\Domain\Model\ValueObject\Email;

final class RegisterUserService
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Email $email, $password):bool
    {
        $user  = User::register( $email, $password);
        $response = $this->repository->registerNewUser($user);
        return $response;
    }
}