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
            $this->email = new Email($_POST['email']);
            $this->email->validate();
            $this->password = $_POST['password'];
            try {
                if ($this->registerUserService->__invoke($this->email, $this->password)) {
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
        else if($this->args){
            $this->email = new Email($this->args[1]);
            $this->email->validate();
            $this->password = $this->args[2];
            try {
                if ($this->registerUserService->__invoke($this->email, $this->password)) {
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
        else
        {
            echo $this->twig->render('Pages/register.twig', ['title' => 'Registration']);
        }
    }
}