<?php

define('LIB_PATH', __DIR__ . '/');

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

require_once LIB_PATH . 'Core/Autoloader.php';

use \Core\Autoloader;

/*
 * Register the autoload mechanism for assuring all defined classes will load 
 * without the need of using requires.
 */
Autoloader::i()->register();
