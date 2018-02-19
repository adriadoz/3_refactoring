<?php
declare(strict_types=1);

namespace LoginApp\Infrastructure\Controllers;

use LoginApp\Application\Service\RegisterUserService;
use LoginApp\Domain\Model\ValueObject\Email;

final class RegisterController
{
    private $twig;
    private $email;
    private $password;
    private $args;
    private $registerUserService;

    public function __construct($args, \Twig_Environment $twig, RegisterUserService $registerUserService)
    {
        $this->twig = $twig;
        $this->args = $args;
        $this->registerUserService = $registerUserService;
    }

    public function __invoke():void
    {
        if ($_POST)
        {
            $this->postNewUser();
        }
        else if($this->args){
            $this->cliNewUser();
        }
        else
        {
            echo $this->twig->render('Pages/register.twig', ['title' => 'Registration']);
        }
    }

    private function registerUser():bool
    {
        return $this->registerUserService->__invoke($this->email, $this->password);
    }

    private function postNewUser():void
    {
        $this->email = new Email($_POST['email']);
        $this->email->validate();
        $this->password = $_POST['password'];
        try {
            if ($this->registerUser()) {
                echo $this->twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Successful registration.']);
            } else {
                echo $this->twig->render('Pages/response.twig', ['title' => 'Registration', 'message' => 'Unable to register.', 'backvisible' => true, 'backlink' => 'register.php', 'backtext' => 'Please try again.']);
            }
        }
        catch (\PDOException $exception)
        {
            echo "Error: " . $exception->getMessage();
        }
    }

    private function cliNewUser():void
    {
        $this->email = new Email($this->args[1]);
        $this->email->validate();
        $this->password = $this->args[2];
        try {
            if ($this->registerUser()) {
                echo 'Successful registration.';
            } else {
                echo 'Unable to register.';
            }
        }
        catch (\PDOException $exception)
        {
            echo "Error: " . $exception->getMessage();
        }
    }
}