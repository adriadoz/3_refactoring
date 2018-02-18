<?php
declare(strict_types=1);

namespace LoginApp\Domain\Model\ValueObject;

final class Email
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->email;
    }

    public function validate()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
        {
            throw new \InvalidArgumentException('Please, introduce a valid Email.');
        }
    }
}