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
     * Login action. If no post data was sended - move to Auth page with error
     * If Auth success - move to chat
     */
    public function login(){
        if(empty($_POST['login']) || empty($_POST['pass'])){
           header('Location: /Auth/',301);
        }

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $res = $user->login();
        if($res){
            header('Location: /Chat/',301);
        }else{
            $error = "Not valid login or password";
            include "Auth/index.php";
        }
    }
}
