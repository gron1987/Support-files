<?php
/**
 * File: UserIF.php.
 * User: gron
 * Date: 3/31/12
 * Time: 11:03 AM
 */
namespace Auth;

/**
 * Interface to work with mapper
 */
interface UserIF
{
    /**
     * Login user
     */
    public function login();
    /**
     * Check user login
     */
    public function checkLogin();
    /**
     * Login from session
     */
    public function loginFromSession();
    /**
     * Return current user data
     */
    public function getCurrentUserData();
    /**
     * Get user data by ID
     * @param int $userId
     */
    public function getUserData($userId);
    /**
     * Update user activity
     */
    public function updateActivity();
    /**
     * Get online users
     * @param int $chatId
     */
    public function getOnlineUsers($chatId);
    /**
     * Get ID property
     */
    public function getId();
    /**
     * Get login property
     */
    public function getLogin();
    /**
     * Get last update property
     */
    public function getLastUpdate();
    /**
     * Return HTML representation
     * @param array $data
     */
    public function createHTMLOnlineUsers(array $data);
}
