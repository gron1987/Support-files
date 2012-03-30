<?php
/**
 * File: DbConnectionIFnnectionIF.php: gron
 * Date: 3/25/12
 * Time: 7:41 PM
 */
namespace Core;

/**
 * Inerface for DB
 */
interface DbConnectionIF
{
    /**
     * @static
     */
    public static function getInstance();
    /**
     * @static
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     */
    public static function reInit($host, $username, $password, $db);
    /**
     * Reset instance. Set $_instance to NULL
     * @static
     */
    public static function reset();

    /**
     * Return host property
     */
    public function getHost();
    /**
     * Return username property
     */
    public function getUsername();
    /**
     * Return password property
     */
    public function getPassword();
    /**
     * Return DB property
     */
    public function getDb();
    /**
     * Return PDO object
     */
    public function getPDO();
}
