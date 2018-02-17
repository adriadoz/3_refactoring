<?php
declare(strict_types=1);

require __DIR__ . '/init.php';

if ($_POST)
{
    try
    {
        // load database connection and password hasher library
        require 'libs/DbConnect.php';
        require 'libs/PasswordHash.php';

        // prepare query
        $query = "select email, password from users where email = :email limit 0,1";
        $statement  = $con->prepare($query);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // this will represent the first question mark
        $statement->bindParam(':email', $email, \PDO::PARAM_STR);

        // execute our query
        $statement->execute();

        // count the rows returned
        $num = $statement->rowCount();

        if ($num == 1) {

            //store retrieved row to a 'row' variable
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            // hashed password saved in the database
            $storedPassword = $row['password'];

            // salt and entered password by the user
            $salt                 = "ilovecodeofaninjabymikedalisay";
            $postedPassword       = $_POST['password'];
            $saltedPostedPassword = $salt . $postedPassword;

            // instantiate PasswordHash to check if it is a valid password
            $hasher = new PasswordHash(8, false);
            $check  = $hasher->CheckPassword($saltedPostedPassword, $storedPassword);

            /*
             * access granted, for the next steps,
             * you may use my php login script with php sessions tutorial :)
             */
            if ($check) {
                echo $twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access granted.']);
            } // $check variable is false, access denied.
            else {
                echo $twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access denied.', 'backvisible'=>true, 'backlink'=>'login.php', 'backtext'=>'Back.']);
            }
        } // no rows returned, access denied
        else {
            echo $twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Access denied.', 'backvisible'=>true, 'backlink'=>'login.php', 'backtext'=>'Back.']);
        }
    } //to handle error
    catch (PDOException $exception)
    {
        echo "Error: " . $exception->getMessage();
    }
}
else
{
    echo $twig->render('Pages/login.twig', ['title' => 'Login']);
}

