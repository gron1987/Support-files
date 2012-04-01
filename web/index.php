<?php

header("X-Frame-Options: SAMEORIGIN"); // Clickjacking , avoid view from IFRAME

use Core\Loader;
use Core\SL;

session_start();

define('PROJECT_PATH', realpath(dirname(__FILE__) . '/../') . '/');
define('MICROSECOND',10000);

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

function smiles($text){
    //TODO: Add smiles list
    $text = stripslashes(htmlspecialchars($text));
    $smiles = array(
        "/:\)/" => '<img src="http://i.smiles2k.net/icq_smiles/1.gif" />',
        "/:\(/" => '<img src="http://i.smiles2k.net/icq_smiles/2.gif" />',
        "/;\)/" => '<img src="http://i.smiles2k.net/icq_smiles/3.gif" />',
        "/:D/i" => '<img src="http://i.smiles2k.net/icq_smiles/4.gif" />',
        "/:P/i" => '<img src="http://i.smiles2k.net/icq_smiles/5.gif" />',
        "/;\(/i" => '<img src="http://i.smiles2k.net/icq_smiles/6.gif" />',
    );

    $smileList = array_keys($smiles);
    $smileImages = array_values($smiles);

    $text = preg_replace($smileList,$smileImages,$text);
    return $text;
}

spl_autoload_register('autoload');
spl_autoload_register('autoload_ext');
set_include_path(PROJECT_PATH . 'view/');

SL::init();

try {
    $loader = new Loader();
    $loader->init($_SERVER['REQUEST_URI']);
    $loader->loadClass();
    $loader->loadMethod();
} catch (\RuntimeException $e) {
    echo 'Error <br />';
    echo $e->getMessage();
    exit;
}
?>
