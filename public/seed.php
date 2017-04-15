<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'fred');
define('DB_PASS', 'smith');
define('DB_NAME', 'mathsdb');

require_once __DIR__ ."/../vendor/autoload.php";

use Itb\UserRepository;
use Itb\User;

$repo = new UserRepository();

        $b1 = new User();
        $b1->setUsername('brown');
        $b1->setPassword('brown');
        $repo->create($b1);

        $b2 = new User();
        $b2->setUsername('black');
        $b2->setPassword('black');
        $repo->create($b2);

        $b3 = new User();
        $b3->setUsername('blue');
        $b3->setPassword('blue');
        $repo->create($b3);

