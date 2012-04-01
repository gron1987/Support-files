<?php
/**
 * File: UserMapper.php.
 * User: gron
 * Date: 3/31/12
 * Time: 11:25 AM
 */
namespace Auth;

use Core\DbConnection;
use Auth\UserMapperIF;

/**
 * Mapper to work with TABLE_NAME table in DB with user data
 */
class UserMapper implements UserMapperIF
{
    /**
     * Active user time in miliseconds
     * 10000 = 1 sec
     * Default 60 seconds (1 min)
     */
    const ACTIVE_TIME = 600000;

    /**
     * Virtual memory cache
     * To avoid meny dublicate queries to DB
     * @var array
     */
    private static $_usersData = array();

    /**
     * Get user data or false. Try to get data from $_POST
     * @return array|bool
     */
    public function login(){
        $sql = "
            SELECT *
            FROM " . self::TABLE_NAME ."
            WHERE login = ? and password = ?
        ";

        $query = DbConnection::getInstance()->execute($sql,array($_POST['login'],md5($_POST['pass'])));
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        if($result){
            return $result;
        }
        return false;

    }

    /**
     * Get user data from $_SESSION
     * @return array
     */
    public function loginBySession(){
        $sql = "
            SELECT *
            FROM " . self::TABLE_NAME ."
            WHERE md5(id) = ? and password = ?
        ";

        $query = DbConnection::getInstance()->execute($sql,array($_SESSION['id'],$_SESSION['pass']));
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        static::$_usersData[$result['id']] = $result;

        return $result;
    }

    /**
     * Check is login in DB or not
     * @param string $login
     * @return bool
     */
    public function validateLogin($login){
        $sql = "
            SELECT COUNT(id) as kol
            FROM " . self::TABLE_NAME . "
            WHERE login = ?
        ";

        $query = DbConnection::getInstance()->execute($sql,array($login));
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        if($result['kol'] === 0){
            return true;
        }
        return false;
    }

    /**
     * Get user data by ID, use virtual cache if it's possible
     * @param $id
     * @return bool|mixed
     */
    public function getUserById($id)
    {
        $cache = $this->_getDataFromCache($id);
        if($cache){
            return $cache;
        }

        $sql = "
            SELECT *
            FROM " . self::TABLE_NAME . "
            WHERE id = ?
        ";

        $query = DbConnection::getInstance()->execute($sql,array($id));
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        static::$_usersData[$result['id']] = $result;

        return $result;
    }

    /**
     * Get data from virtual cache
     * @param int $id
     * @return bool
     */
    private function _getDataFromCache($id){
        if(!empty(static::$_usersData[$id])){
            return static::$_usersData[$id];
        }
        return false;
    }

    /**
     * Update last user activity. UserId get from $_SESSION
     * @return bool
     */
    public function updateLastActivity(){
        if((int) $_SESSION['userid'] < 1){
            return false;
        }

        $sql = "
            UPDATE " . self::TABLE_NAME . "
            SET `last_update` = ?
            WHERE id = ?
        ";

        DbConnection::getInstance()->execute($sql,array(microtime(true)*MICROSECOND,$_SESSION['userid']));
        return true;
    }

    /**
     * Get users with last activity in 60 seconds
     * @param int $chatId
     * @return array
     */
    public function getOnlineUsers($chatId){
        //TODO: From chat id
        $sql = "
            SELECT *
            FROM " . self::TABLE_NAME . "
            WHERE `last_update` >= ? AND `last_update` <= ?
            ORDER BY `login` ASC
        ";

        $to = microtime(true)*MICROSECOND;
        $from = $to - static::ACTIVE_TIME;

        $query = DbConnection::getInstance()->execute($sql,array($from,$to));
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}
