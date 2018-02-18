<?php
declare(strict_types=1);

namespace LoginApp\Domain\Model\User;

use LoginApp\Domain\Model\ValueObject\Email;
use LoginApp\Domain\Model\LegacyHash\PasswordHash;

final class User
{
    private $email;
    private $hashPassword;

    public function __construct(Email $email, $hashPassword)
    {
        $this->email    = $email;
        $this->hashPassword = $hashPassword;
    }

    public static function register(Email $email, $password)
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

    public function validate($password):bool
    {
        if (!password_verify ( $password , $this->hashPassword ))
        {
            return false;
        }
        return true;
    }

    public function validateLegacy($password):bool
    {
        $salt                 = "ilovecodeofaninjabymikedalisay";
        $postedPassword       = $_POST['password'];
        $saltedPostedPassword = $salt . $postedPassword;
        $hasher = new PasswordHash(8, false);
        return $check  = $hasher->CheckPassword($saltedPostedPassword, $this->hashPassword);
    }
}