<?php
/**
 * File: AuthControllerController.php.
 * User: gron
 * Date: 3/31/12
 * Time: 11:03 AM
 */
namespace Auth;

use Core\SL;

/**
 * Class to work with /Auth/ link
 */
class AuthController
{
    /**
     * Main screen, if user authorized ($_SESSION) then redirect to chat window
     */
    public function index(){
        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $res = $user->loginFromSession();
        if($res){
            header('Location: /Chat/');
            exit;
        }

        include "Auth/index.php";
    }

    /**
     * Update user activity by JS script (setInterval in js)
     */
    public function updateActivity(){
        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $user->updateActivity();
    }

    /**
     * Check is user logged in use.
     * TRUE if login in use now.
     * Output HTML.
     */
    public function checkLogin(){
        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $result = $user->checkLogin($_REQUEST['login']);

        echo json_encode(array('result' => $result));
    }

    /**
     * Login action. If no post data was sended - move to Auth page with error
     * If Auth success - move to chat
     */
    public function login(){
        if(empty($_POST['login']) || empty($_POST['pass'])){
           header('Location: /Auth/');
            exit;
        }

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $res = $user->login();
        if($res){
            header('Location: /Chat/');
            exit;
        }else{
            $error = "Not valid login or password";
            include "Auth/index.php";
        }
    }

    /**
     * Register new user
     */
    public function registerUser(){
        if($_POST['pass'] != $_POST['repass']){
            header('Location: /Auth/');
            exit;
        }

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $result = $user->registerUser($_POST['login'],$_POST['pass']);

        if($result){
            header('Location: /Chat/');
            exit;
        }else{
            header('Location: /Auth/');
            exit;
        }
    }

    public function socialAuth(){
        $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $data = json_decode($s, true);

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $result = $user->loginBySocial($data['network'],$data['uid']);

        if($result){
            header('Location: /Chat/');
            exit;
        }else{
            header('Location: /Auth/');
            exit;
        }
    }

    public function socialAdd(){
        $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $data = json_decode($s, true);

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $user->setSocialNetwork($data['network'],$data['uid']);
        $user->setUsername($data['first_name'].' '.$data['last_name']);

        header('Location: /Chat/');
        exit;
    }

    public function logout(){
        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $user->logout();

        header('Location: /Auth/');
        exit;
    }
}
