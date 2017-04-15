<?php

session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'fred');
define('DB_PASS', 'smith');
define('DB_NAME', 'bibliography_manager');

require_once __DIR__ . '/../vendor/autoload.php';

use Itb\WebApplication;
$app = new WebApplication();
$app ->run();

