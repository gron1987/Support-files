<?php
/**
 * File: MessagesMapperIF.php.
 * User: gron
 * Date: 3/31/12
 * Time: 6:30 PM
 */
namespace Chat;

/**
 * Interface to work with messages in DB
 */
interface MessagesMapperIF
{
    /**
     * Table name for messages
     */
    const TABLE_NAME = "messages";

    /**
     * Add message to DB
     * @param $message
     * @param int $idUserTo
     * @param int $private
     * @param int $idChat
     */
    public function addMessage($message, $idUserTo = 0, $private = 0, $idChat = 0);

    /**
     * Return all messages with limit
     * @param $idChat
     * @param int $limit
     */
    public function getMessagesFromChat($idChat, $limit = 40);

    /**
     * Return unreaded messages
     * @param int $idChat
     */
    public function getUnreadedMessagesFromChat($idChat);

}
