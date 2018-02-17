<?php
declare(strict_types=1);

namespace Infrastructure\Controllers;

final class Controller
{
    private $pageType;
    private $loader;
    private $twig;

    public function __construct($pageType)
    {
        $this->pageType = $pageType;
        $this->loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Templates');
        $this->twig = new \Twig_Environment( $this->loader, ['debug' => true] );
    }

    public function __invoke():void
    {
        if ($_POST)
        {
            try
            {
                require 'libs/DbConnect.php';
                require 'libs/PasswordHash.php';

                $query = "select email, password from users where email = :email limit 0,1";
                $statement  = $con->prepare($query);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                $statement->bindParam(':email', $email, \PDO::PARAM_STR);

                $statement->execute();

                $num = $statement->rowCount();

                if ($num == 1) {

                    $row = $statement->fetch(PDO::FETCH_ASSOC);

                    $storedPassword = $row['password'];

                    $salt                 = "ilovecodeofaninjabymikedalisay";
                    $postedPassword       = $_POST['password'];
                    $saltedPostedPassword = $salt . $postedPassword;

                    $hasher = new PasswordHash(8, false);
                    $check  = $hasher->CheckPassword($saltedPostedPassword, $storedPassword);

                    if ($check) {
                        echo $this->$twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access granted.']);
                    }
                    else {
                        echo $this->$twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access denied.', 'backvisible'=>true, 'backlink'=>'login.php', 'backtext'=>'Back.']);
                    }
                }
                else {
                    echo $this->$twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access denied.', 'backvisible'=>true, 'backlink'=>'login.php', 'backtext'=>'Back.']);
                }
            }
            catch (PDOException $exception)
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