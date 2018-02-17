<?php
declare(strict_types=1);

require __DIR__ . '/init.php';


if ($_POST) {

    try {
        // load database connection and password hasher library
        require 'libs/DbConnect.php';
        require 'libs/PasswordHash.php';

        /*
         * -prepare password to be saved
         * -concatinate the salt and entered password
         */
        $salt     = "ilovecodeofaninjabymikedalisay";
        $password = $salt . $_POST['password'];

        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        /*
         * '8' - base-2 logarithm of the iteration count used for password stretching
         * 'false' - do we require the hashes to be portable to older systems (less secure)?
         */
        $hasher   = new PasswordHash(8, false);
        $password = $hasher->HashPassword($password);

        // insert command
        $query = "INSERT INTO users SET email = :email, password = :password";

        $statement = $con->prepare($query);

        $statement->bindParam(':email', $email, \PDO::PARAM_STR);
        $statement->bindParam(':password', $password, \PDO::PARAM_STR);

        // execute the query
        if ($statement->execute()) {
            echo $twig->render('Pages/response.twig', ['title' => 'Login', 'message' => 'Successful registration.']);
        } else {
            echo $twig->render('Pages/response.twig', ['title' => 'Registration', 'message' => 'Unable to register.', 'backvisible'=>true, 'backlink'=>'register.php', 'backtext'=>'Please try again.']);
        }
    } //to handle error
    catch (PDOException $exception) {
        echo "Error: " . $exception->getMessage();
    }
}
else {
    echo $twig->render('Pages/register.twig', ['title' => 'Registration']);
}


