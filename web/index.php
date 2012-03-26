<?php

use Core\Loader;
use Core\DI;

define('PROJECT_PATH', dirname(__FILE__) . '/');

/**
 * Load class by it's namespace (\\ change to /)
 * @param string $className
 */
function autoload($className)
{
    $className = str_replace('\\', '/', $className);
    $link = PROJECT_PATH . $className . '.php';
    if (is_readable($link)) {
        include_once $link;
    }
}
/**
 * Load class from extenstion directory
 * @param string $className
 */
function autoload_ext($className)
{
    $className = 'extension/' . str_replace('\\', '/', $className);
    autoload($className);
}

spl_autoload_register('autoload');
spl_autoload_register('autoload_ext');

DI::init();

try {
    $loader = new Loader();
    $loader->init($_SERVER['REQUEST_URI']);
    $loader->loadClass();
    $loader->loadMethod();
} catch (\InvalidArgumentException $e) {
    // error in init()
    echo 'Error <br />';
    echo $e->getMessage();
    exit;
} catch (\BadMethodCallException $e) {
    // error in loadMethod()
    echo 'Error <br />';
    echo $e->getMessage();
    exit;
}
?>
