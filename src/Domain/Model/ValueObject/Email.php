<?php
declare(strict_types=1);

namespace Domain\Model\ValueObject;

final class Email
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }
}