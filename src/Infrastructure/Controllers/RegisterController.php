<?php
declare(strict_types=1);

namespace LoginApp\Infrastructure\Controllers;

use LoginApp\Application\Service\RegisterUserService;
use LoginApp\Domain\Model\ValueObject\Email;
use LoginApp\Infrastructure\Repository\MySQL\MySqlUserRepository;

final class RegisterController
{
    private $loader;
    private $twig;
    private $email;
    private $password;
    private $args;

    public function __construct($args)
    {
        $this->loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Templates');
        $this->twig = new \Twig_Environment( $this->loader, ['debug' => true] );
        $this->args = $args;
    }

    public function __invoke():void
    {
        if ($_POST)
        {
            $this->email = new Email($_POST['email']);
            $this->email->validate();
            $this->password = $_POST['password'];
            try {
                $mySqlUserRepo = new MySqlUserRepository();
                $registerUserService = new RegisterUserService($mySqlUserRepo);
                if ($registerUserService->__invoke($this->email, $this->password)) {
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
                $mySqlUserRepo = new MySqlUserRepository();
                $registerUserService = new RegisterUserService($mySqlUserRepo);
                if ($registerUserService->__invoke($this->email, $this->password)) {
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