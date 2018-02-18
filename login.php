<?php
declare(strict_types=1);

require __DIR__ . '/init.php';

use LoginApp\Infrastructure\Controllers\LoginController;

$controller = new LoginController();
$controller->__invoke();