<?php
declare(strict_types=1);

require __DIR__ . '/init.php';

use LoginApp\Infrastructure\Controllers\RegisterController;

$args = null;
if(count($argv)>1){
    $args = $argv;
}
$controller = new RegisterController($args);
$controller->__invoke();