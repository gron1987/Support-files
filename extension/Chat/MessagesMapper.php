<?php
/**
 * File: MessagesMapper.php.
 * User: gron
 * Date: 3/31/12
 * Time: 5:53 PM
 */
namespace Chat;

use Core\SL;
use Core\DbConnection;
use Chat\MessagesMapperIF;

/**
 * Class to work with TABLE_NAME table with chat messages
 */
class MessagesMapper implements MessagesMapperIF
{
    /**
     * Max length of message (in symbols)
     */
    const MESSAGE_MAX_LENGTH = 500;

    /**
     * Add message to DB
     * @param string $message
     * @param int $idUserTo
     * @param int $private
     * @param int $idChat
     * @return bool
     */
    public function addMessage($message, $idUserTo = 0, $private = 0, $idChat = 1)
    {
        if (empty($message)) {
            return false;
        }

        $message = substr($message,0,static::MESSAGE_MAX_LENGTH);

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $user->getCurrentUserData();
        $sql = "
            INSERT INTO " . static::TABLE_NAME . " (id_user_from,id_user_to,id_chat,message,time,private)
            VALUES (?,?,?,?,?,?)
        ";


        DbConnection::getInstance()->execute($sql, array(
            $user->getId(),
            $idUserTo,
            $idChat,
            $message,
            microtime(true)*MICROSECOND,
            $private
        ));

        return true;
    }

    /**
     * Get last messages from DB with limit
     * @param int $idChat
     * @param int $limit
     * @return array
     */
    public function getMessagesFromChat($idChat,$limit = 30){
        $limit = (int)$limit;

        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $user->getCurrentUserData();

        $sql = "
            SELECT *
            FROM " . static::TABLE_NAME . "
            WHERE `id_chat` = :idchat AND
            ( `private` = 0 OR `id_user_from` = :iduser OR `id_user_to` = :iduser )
            ORDER BY `time` DESC LIMIT 0,$limit
        ";

        $query = DbConnection::getInstance()->execute($sql, array(
            'idchat' => $idChat,
            'iduser' => $user->getId()
        ));
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        $result = array_reverse($result);

        return $result;
    }

    /**
     * Get messages from DB between user activity
     * @param int $idChat
     * @return array
     */
    public function getUnreadedMessagesFromChat($idChat){
        /**
         * @var $user \Auth\User
         */
        $user = SL::create('AuthUser');
        $user->getCurrentUserData();

        $sql = "
            SELECT *
            FROM " . static::TABLE_NAME . "
            WHERE `id_chat` = :idchat AND
            ( `private` = 0 OR `id_user_from` = :userid OR `id_user_to` = :userid )
            AND `time` >= :fromtime AND `time` <= :totime
            ORDER BY `time` ASC
        ";

        $query = DbConnection::getInstance()->execute($sql,array(
            'idchat' => $idChat,
            'userid' => $user->getId(),
            'fromtime' => $user->getLastUpdate(),
            'totime' => microtime(true)*MICROSECOND
        ));

        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}
