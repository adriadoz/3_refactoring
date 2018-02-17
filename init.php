<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

$loader     = new \Twig_Loader_Filesystem(__DIR__ . '/src/Infrastructure/Templates');
$twig       = new \Twig_Environment( $loader, ['debug' => true] );