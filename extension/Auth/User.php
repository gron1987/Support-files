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
    private $_username;
    private $_facebook;
    private $_vk;
    private $_google;

    /**
     * Login user, is login will be success - save data to $_SESSION
     * If cookie auth will be needed - do it here
     * @return bool
     */
    public function login(){
        $mapper = $this->_getUserMapper();
        $login = $mapper->login();
        if(!$login){
            return false;
        }
        $result = $this->_setDataToObject($login);

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
    public function checkLogin($login){
        $mapper = $this->_getUserMapper();
        $res = $mapper->validateLogin($login);

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
            $this->_username = $tableData['username'];
            $this->_facebook = $tableData['facebook'];
            $this->_vk = $tableData['vk'];
            $this->_google = $tableData['google'];
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

    /**
     * Register new user
     * @param string $login
     * @param string $pass
     * @return bool
     */
    public function registerUser($login, $pass)
    {
        if(empty($login) || empty($pass) || (strlen($login) < 3) || (strlen($pass) < 3) ){
            return false;
        }

        if($this->checkLogin($login)){
            return false;
        }

        $mapper = $this->_getUserMapper();
        $id = $mapper->registerUser($login,$pass);

        $_SESSION['userid'] = $id;
        $_SESSION['id'] = md5($id);
        $_SESSION['pass'] = md5($pass);

        return true;
    }

    /**
     * Logout user, remove session data
     */
    public function logout(){
        unset($_SESSION['userid']);
        unset($_SESSION['id']);
        unset($_SESSION['pass']);
    }

    /**
     * Set social network ID
     * @param string $network
     * @param int $id
     */
    public function setSocialNetwork($network,$id){
        $result = false;
        if($_SESSION['userid'] > 0){
            $mapper = $this->_getUserMapper();
            switch($network){
                case "facebook":
                    $mapper->setFacebook($id);
                    break;
                case "vkontakte":
                    $mapper->setVk($id);
                    break;
                case "google":
                    $mapper->setGoogle($id);
                    break;
            }
        }
        return $result;
    }

    /**
     * Get Facebook id
     * @return int
     */
    public function getFacebook(){
        return $this->_facebook;
    }

    /**
     * Get VK id
     * @return int
     */
    public function getVk(){
        return $this->_vk;
    }

    /**
     * Get google id
     * @return int
     */
    public function getGoogle(){
        return $this->_google;
    }

    /**
     * Login user by social network
     * @param $network
     * @param $id
     * @return bool
     */
    public function loginBySocial($network, $id)
    {
        $mapper = $this->_getUserMapper();
        $data = false;
        switch($network){
            case "facebook":
                $data = $mapper->getByFacebook($id);
                break;
            case "vkontakte":
                $data = $mapper->getByVk($id);
                break;
            case "google":
                $data = $mapper->getByGoogle($id);
                break;
        }

        if($data){
            $_SESSION['userid'] = $data['id'];
            $_SESSION['id'] = md5($data['id']);
            $_SESSION['pass'] = $data['password'];

            return true;
        }
        return false;
    }

    /**
     * Set username
     * @param $username
     * @return bool
     */
    public function setUsername($username)
    {
        if(empty($username)){
            return false;
        }

        $mapper = $this->_getUserMapper();
        $mapper->setUsername($username);
        return true;
    }

    /**
     * Return username
     */
    public function getUsername()
    {
        return $this->_username;
    }
}
