<?php
/**
 * File: User.php.
 * User: gron
 * Date: 3/31/12
 * Time: 2:03 PM
 */
namespace Auth;

use Core\SL;
use Auth\UserIF;

/**
 * Class to work with UserMapperIF
 */
class User implements UserIF
{
    /**
     * If no user with id will be found this nickname will be used
     */
    const DELETED_LOGIN = "Deleted";
    /**
     * Template to create right block (online users list)
     */
    const ONLINE_USER_TEMPLATE = "Chat/users.php";

    private $_id;
    private $_login;
    private $_password;
    private $_lastUpdate;

    /**
     * Login user, is login will be success - save data to $_SESSION
     * If cookie auth will be needed - do it here
     * @return bool
     */
    public function login(){
        $mapper = $this->_getUserMapper();
        $result = $this->_setDataToObject($mapper->login());

        if($result){
            $_SESSION['userid'] = $this->_id;
            $_SESSION['id'] = md5($this->_id);
            $_SESSION['pass'] = $this->_password;
        }

        return $result;
    }

    /**
     * Check login for free
     * @return bool
     */
    public function checkLogin(){
        $mapper = $this->_getUserMapper();
        $res = $mapper->validateLogin($_REQUEST['login']);

        return $res;
    }

    /**
     * Login from $_SESSION data (if it's not empty)
     * @return bool
     */
    public function loginFromSession(){
        if(!empty($_SESSION['id']) && !empty($_SESSION['pass'])){
            $mapper = $this->_getUserMapper();
            $result = $this->_setDataToObject($mapper->loginBySession());
            return $result;
        }else{
            return false;
        }
    }

    /**
     * @return \Auth\UserMapper
     */
    private function _getUserMapper(){
        $mapper = SL::create('UserMapper');
        return $mapper;
    }

    /**
     * Get user data by getUserData method (and set object properties), but id get from session.
     * @see getUserData
     * @return \Auth\User
     */
    public function getCurrentUserData()
    {
        return $this->getUserData($_SESSION['userid']);
    }

    /**
     * Get user data by ID. If nothing will be found - return false but change username to DELETED_LOGIN
     * @param $userId
     * @return bool
     */
    public function getUserData($userId)
    {
        $mapper = $this->_getUserMapper();
        $result = $mapper->getUserById($userId);
        if($result){
            return $this->_setDataToObject($result);
        }

        $this->_login = self::DELETED_LOGIN;

        return false;
    }

    /**
     * Set daya from array to this object
     * @param array $tableData
     * @return bool
     */
    private function _setDataToObject(array $tableData){
        if($tableData){
            $this->_id = $tableData['id'];
            $this->_login = $tableData['login'];
            $this->_password = $tableData['password'];
            $this->_lastUpdate = $tableData['last_update'];
            return true;
        }
        return false;
    }

    /**
     * Update user last activity
     */
    public function updateActivity(){
        $mapper = $this->_getUserMapper();
        $mapper->updateLastActivity();
    }

    /**
     * Get array of online users
     * @param int $chatId
     * @return array
     */
    public function getOnlineUsers($chatId){
        $mapper = $this->_getUserMapper();
        return $mapper->getOnlineUsers($chatId);
    }

    public function getId(){
        return $this->_id;
    }

    public function getLogin(){
        return $this->_login;
    }

    public function getLastUpdate(){
        return $this->_lastUpdate;
    }

    /**
     * Create HTML representation of online users by ONLINE_USER_TEMPLATE
     * @param array $data
     * @return string
     */
    public function createHTMLOnlineUsers(array $data){
        $html = '';

        foreach($data as $item){
            ob_start();
            include static::ONLINE_USER_TEMPLATE;
            $html .= ob_get_clean();
        }

        return $html;
    }
}
