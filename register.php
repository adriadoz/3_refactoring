<?php
declare(strict_types=1);

require __DIR__ . '/init.php';

use LoginApp\Infrastructure\Controllers\RegisterController;

$controller = new RegisterController();
$controller->__invoke();