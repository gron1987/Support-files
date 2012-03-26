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
     * @abstract
     */
    public static function getInstance();
    /**
     * @static
     * @abstract
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     */
    public static function reInit($host, $username, $password, $db);
    /**
     * @static
     * @abstract
     */
    public static function reset();

    /**
     * @abstract
     */
    public function getHost();
    /**
     * @abstract
     */
    public function getUsername();
    /**
     * @abstract
     */
    public function getPassword();
    /**
     * @abstract
     */
    public function getDb();
    /**
     * @abstract
     */
    public function getPDO();
}
