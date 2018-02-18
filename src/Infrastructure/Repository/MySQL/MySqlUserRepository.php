<?php
declare(strict_types=1);

namespace LoginApp\Infrastructure\Repository\MySQL;

use LoginApp\Domain\Model\ValueObject\Email;
use LoginApp\Domain\Model\User\User;
use LoginApp\Domain\Repository\UserRepositoryInterface;

final class MySqlUserRepository implements UserRepositoryInterface
{
    private $host = "localhost";
    private $db_name  = "coan_secure";
    private $username = "root";
    private $password = "root";
    private $connection;
    public function __construct()
    {
        try {
            $this->connection = new \PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
        }
        catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public function findUserByEmail(Email $email)
    {
        $query = "select email, password from users where email = :email limit 0,1";
        $statement  = $this->connection->prepare($query);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        $statement->bindParam(':email', $email, \PDO::PARAM_STR);

        $statement->execute();

        return $statement;
    }
    public function registerNewUser(User $user):bool
    {
        $query = "INSERT INTO users SET email = :email, password = :password";

        $statement = $this->connection->prepare($query);
        $email = $user->email();
        $password = $user->hashCode();
        $statement->bindParam(':email', $email, \PDO::PARAM_STR);
        $statement->bindParam(':password', $password, \PDO::PARAM_STR);

        return $statement->execute();
    }
}