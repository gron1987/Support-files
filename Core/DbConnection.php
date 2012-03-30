<?php
/**
 * File: DbConnection.phpection.php.
 * User: gron
 * Date: 3/25/12
 * Time: 7:46 PM
 */
namespace Core;

/**
 * Database class
 */
class DbConnection implements DbConnectionIF
{
    /**
     * @var string
     */
    private $_host = 'localhost';
    /**
     * @var string
     */
    private $_username = 'root';
    /**
     * @var string
     */
    private $_password = '';
    /**
     * @var string
     */
    private $_db = 'test';
    /**
     * @var null|\PDO
     */
    private $pdo;
    /**
     * @var DbConnection
     */
    private static $_instance;

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     */
    private function __construct($host = '', $username = '', $password = '', $db = '')
    {
        $this->pdo = null;
        if (!empty($host)) {
            $this->_host = $host;
        }
        if (!empty($username)) {
            $this->_username = $username;
        }
        if (!empty($password)) {
            $this->_password = $password;
        }
        if (!empty($db)) {
            $this->_db = $db;
        }
        $this->pdo = new \PDO("mysql:dbname=" . $this->_db . ";host=" . $this->_host, $this->_username, $this->_password);
    }

    /**
     * @static
     * @return DbConnection
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new DbConnection();
        }
        return self::$_instance;
    }

    /**
     * Reinit with new data
     * @static
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $db
     */
    public static function reInit($host, $username, $password, $db)
    {
        self::$_instance = new DbConnection($host, $username, $password, $db);
    }

    /**
     * Reset instance. Set $_instance to NULL
     * @static
     */
    public static function reset()
    {
        self::$_instance = null;
    }

    /**
     * Return host property
     * @return string
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * Return username property
     * @return string
     */
    public function getUsername()
    {
        $this->getDb();
        return $this->_username;
    }

    /**
     * Return password property
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Return DB property
     * @return string
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * Return PDO object
     * @return null|\PDO
     */
    public function getPDO()
    {
        $this->getPDO();
        return $this->pdo;
    }

    /**
     * Execute query
     * @param $sql
     * @param array $data
     * @return \PDOStatement
     */
    public function execute($sql,$data=array()){
        $query = $this->pdo->prepare($sql);
        $query->execute($data);
        return $query;
    }
}
