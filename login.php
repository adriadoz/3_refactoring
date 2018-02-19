<?php
declare(strict_types=1);

require __DIR__ . '/init.php';

use LoginApp\Infrastructure\Controllers\LoginController;
use LoginApp\Infrastructure\Repository\MySQL\MySqlUserRepository;
use LoginApp\Application\Service\LoginUserService;

$loader = new \Twig_Loader_Filesystem(__DIR__ . '/src/Infrastructure/Templates');
$twig = new \Twig_Environment( $loader, ['debug' => true] );

$mySqlUserRepo = new MySqlUserRepository();
$loginUserService = new LoginUserService($mySqlUserRepo);

$controller = new LoginController($loginUserService, $twig);
$controller->__invoke();