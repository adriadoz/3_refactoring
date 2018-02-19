<?php
declare(strict_types=1);

require __DIR__ . '/init.php';

use LoginApp\Infrastructure\Controllers\RegisterController;
use LoginApp\Infrastructure\Repository\MySQL\MySqlUserRepository;
use LoginApp\Application\Service\RegisterUserService;

$args = null;
if(!is_null($argv) && count($argv)>1){
    $args = $argv;
}

$loader = new \Twig_Loader_Filesystem(__DIR__ . '/src/Infrastructure/Templates');
$twig = new \Twig_Environment( $loader, ['debug' => true] );

$mySqlUserRepo = new MySqlUserRepository();
$registerUserService = new RegisterUserService($mySqlUserRepo);

$controller = new RegisterController($args, $twig, $registerUserService);
$controller->__invoke();