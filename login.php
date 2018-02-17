<?php
declare(strict_types=1);

require __DIR__ . '/init.php';

use Infrastructure\Controllers\Controller;

$controller = new Controller('login');
$controller->__invoke();