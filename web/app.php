<?php

use Composer\Autoload\ClassLoader;
use Symfony\Component\HttpFoundation\Request;

/** @var ClassLoader $loader */
$loader = require __DIR__ . '/../app/autoload.php';
include_once __DIR__ . '/../var/bootstrap.php.cache';

$kernel   = new AppKernel('prod', false);
$request  = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
