<?php
declare(strict_types=1);

namespace Domain\Model;

final class User
{
    private $email;
    private $hashPassword;

    public function __construct(Email $email, $hashPassword)
    {
        $this->email    = $email;
        $this->hashPassword = $hashPassword;
    }

    public static function register($password, $email)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        return new self($email, $hashPassword);
    }

    public function email()
    {
        return $this->email;
    }

    public function hashCode()
    {
        return $this->hashPassword;
    }
}