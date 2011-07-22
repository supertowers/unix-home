<?php

define('LIB_PATH', __DIR__ . '/');

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

function __autoload($className)
{
    // support for namespaces
    $pathName = str_replace('\\', '/', $className);

    if (file_exists(LIB_PATH . $pathName . '.php'))
    {
        require_once(LIB_PATH . $pathName . '.php');
    }
}

