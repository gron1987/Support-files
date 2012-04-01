<?php
/**
 * File: ChatController.phproller.php.
 * User: gron
 * Date: 3/31/12
 * Time: 1:59 PM
 */
namespace Chat;

use Core\SL;

/**
 * Class to work with /Chat/ link
 */
class ChatController
{
    /**
     * Constructor, check is user authorized or not
     */
    public function __construct(){
        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $res = $user->loginFromSession();
        if(!$res){
            header('Location: /Auth/');
            exit;
        }
    }

    /**
     * Main chat screen
     */
    public function index(){
        /**
         * @var $messages \Chat\Messages
         */
        $messages = SL::create('ChatMessages');
        $data = $messages->getMainChatMessages($messages::MAIN_CHAT_ID);
        $html = $messages->createHTMLFromMessages($data);

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $onlineUsers = $user->getOnlineUsers(0);
        $onlineUsers = array_reverse($onlineUsers);
        $htmlOnlineUsers = $user->createHTMLOnlineUsers($onlineUsers);

        $user->getCurrentUserData();

        include "Chat/index.php";
    }

    /**
     * Add message to chat
     */
    public function addMessage(){
        $error = false;

        /**
         * @var $messages \Chat\Messages
         */
        $messages = SL::create('ChatMessages');
        $message = $_POST['message'];
        $userTo = !empty($_POST['id_user']) ? $_POST['id_user'] : 0 ;
        $private = !empty($_POST['private']) ? $_POST['private'] : 0 ;
        $idChat = !empty($_POST['id_chat']) ? $_POST['id_chat'] : $messages::MAIN_CHAT_ID ;

        if(GLOBAL_CHAR_FORBIDDEN){
            if(($idChat == $messages::MAIN_CHAT_ID) && ($userTo == 0) ){
                echo json_encode(array('error' => "Can't send message to all in main chat"));
                exit;
            }
        }

        if(!$error){
            $messages->addMessage($message,$userTo,$private,$idChat);
        }
    }

    /**
     * Get new messages.
     * Output JSON
     */
    public function getNewData(){
        //TODO: Rewrite this to new logic of messages
        /**
         * @var $messages \Chat\Messages
         */
        $messages = SL::create('ChatMessages');
        $data = $messages->getUnreadedMessagesFromChat($messages::MAIN_CHAT_ID);
        $html = $messages->createHTMLFromMessages($data);

        $json = array();
        $json['chat'] = $html;
        $json['notification'] = array(
            'totalCount' => sizeOf($data),
            'privateCount' => $messages->getPrivateMessagesCountFromData($data)
        );

        echo json_encode($json);
    }

    /**
     * Get user list.
     * Output JSON
     */
    public function getUserList(){
        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $onlineUsers = $user->getOnlineUsers(0);
        $onlineUsers = array_reverse($onlineUsers);
        $htmlOnlineUsers = $user->createHTMLOnlineUsers($onlineUsers);

        $json = array();
        $json['users'] = $htmlOnlineUsers;

        echo json_encode($json);
    }
}
