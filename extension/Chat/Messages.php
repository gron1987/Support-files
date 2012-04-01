<?php
/**
 * File: Messages.php.
 * User: gron
 * Date: 3/31/12
 * Time: 6:34 PM
 */
namespace Chat;

use Core\SL;

/**
 * Object to work with messages and messagesMapperIF
 * Maybe I need to divide this class for creation array of Message object
 */
class Messages
{
    /**
     * Main chat ID as constant to use (don't use "magic numbers")
     */
    const MAIN_CHAT_ID = 1;
    /**
     * Message representation template path
     */
    const MESSAGE_TEMPLATE = "Chat/messages.php";

    /**
     * Add message to chat
     * @param string $message
     * @param int $idUserTo
     * @param int $private
     * @param int $idChat
     * @return bool
     */
    public function addMessage($message, $idUserTo = 0, $private = 0, $idChat = 1)
    {
        /**
         * @var $messagesMapper \Chat\MessagesMapper
         */
        $messagesMapper = SL::create('MessagesMapper');
        $result = $messagesMapper->addMessage($message, $idUserTo, $private, $idChat);
        return $result;
    }

    /**
     * Get all messages from main chat
     * @param int $chatId
     * @return array
     */
    public function getMainChatMessages($chatId){
        /**
         * @var $messagesMapper \Chat\MessagesMapper
         */
        $messagesMapper = SL::create('MessagesMapper');
        $result = $messagesMapper->getMessagesFromChat($chatId);

        return $result;
    }

    /**
     * Get unreaded messages ( messages between user activity )
     * @param int $idChat
     * @return array
     */
    public function getUnreadedMessagesFromChat($idChat){
        /**
         * @var $messagesMapper \Chat\MessagesMapper
         */
        $messagesMapper = SL::create('MessagesMapper');
        $result = $messagesMapper->getUnreadedMessagesFromChat($idChat);

        return $result;
    }

    /**
     * Get private messages from data array
     * @param array $data
     * @return int
     */
    public function getPrivateMessagesCountFromData(array $data){
        $count = 0;
        foreach($data as $item){
            if($item['private'] == 1){
                $count++;
            }
        }

        return $count;
    }

    /**
     * Create HTML from array with messages, use MESSAGE_TEMPLATE for html representation
     * @param array $messages
     * @return array
     */
    public function createHTMLFromMessages(array $messages){
        $html = array();

        foreach($messages as $item){
            $chat_name = "chat_";
            // chat name by userID from small to big (to prevent 1_2 , 2_1)
            $userIds = array($item['id_user_to'],$item['id_user_from']);
            asort($userIds);
            foreach($userIds as $id){
                $chat_name .= $id . "_";
            }
            $chat_name = rtrim($chat_name,"_");

            if(!isset($html[$chat_name])){
                $html[$chat_name] = "";
            }

            /**
             * @var $userFrom \Auth\User
             */
            $userFrom = SL::create('AuthUser');
            $userFrom->getUserData($item['id_user_from']);

            if($item['id_user_to'] != 0){
                /**
                 * @var $userTo \Auth\User
                 */
                $userTo = SL::create('AuthUser');
                $userTo->getUserData($item['id_user_to']);
            }else{
                $userTo = null;
            }

            ob_start();
            include static::MESSAGE_TEMPLATE;
            $html[$chat_name] .= ob_get_clean();
        }

        return $html;
    }
}