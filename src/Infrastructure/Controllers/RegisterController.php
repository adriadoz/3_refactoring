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

    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Templates');
        $this->twig = new \Twig_Environment( $this->loader, ['debug' => true] );
    }

    public function __invoke():void
    {
        if ($_POST)
        {
            $this->email = new Email($_POST['email']);
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
            catch (PDOException $exception)
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