<?php
declare(strict_types=1);

namespace LoginApp\Infrastructure\Controllers;

use LoginApp\Domain\Model\ValueObject\Email;
use LoginApp\Infrastructure\Repository\MySQL\MySqlUserRepository;
use LoginApp\Application\Service\LoginUserService;

final class LoginController
{
    private $loader;
    private $twig;

    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Templates');
        $this->twig = new \Twig_Environment( $this->loader, ['debug' => true] );
    }

    public function __invoke():void
    {
        if ($_POST)
        {
            try
            {
                $email = new Email($_POST['email']);
                $mySqlUserRepo = new MySqlUserRepository();
                $loginUserService = new LoginUserService($mySqlUserRepo);
                if($loginUserService->__invoke($email,$_POST['password'])){
                    echo $this->twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access granted.']);
                } else {
                    echo $this->twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access denied.', 'backvisible' => true, 'backlink' => 'login.php', 'backtext' => 'Back.']);
                }
            }
            catch (\PDOException $exception)
            {
                echo "Error: " . $exception->getMessage();
            }
        }
        else
        {
            echo $this->twig->render('Pages/login.twig', ['title' => 'Login']);
        }
    }
}