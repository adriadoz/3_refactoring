<?php
declare(strict_types=1);

namespace LoginApp\Application\Service;

use LoginApp\Domain\Model\User\User;
use LoginApp\Domain\Model\ValueObject\Email;
use LoginApp\Domain\Repository\UserRepositoryInterface;

final class LoginUserService
{
    private const ONE_USER = 1;
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Email $email, $password):bool
    {
        $response = $this->repository->findUserByEmail($email);

        if($response->rowCount() !== $this::ONE_USER){
            return false;
        }

        $row = $response->fetch(\PDO::FETCH_ASSOC);
        $dbEmail = new Email($row['email']);
        $dbUser = new User($dbEmail,$row['password']);

        if($dbUser->validate($password)){
            return true;
        }

        return $dbUser->validateLegacy($password);
    }
}