<?php

require_once __DIR__ . '/Core/Loader.php';
spl_autoload_register(array('Core\Loader', 'load'));

Core\Loader::registerPath(__DIR__);
Core\Loader::registerPath(__DIR__ . '/../src');