<?php
/**
 * File: UserMapperIF.php.
 * User: gron
 * Date: 3/31/12
 * Time: 11:25 AM
 */
namespace Auth;

/**
 * Interface to work with user table
 */
interface UserMapperIF
{
    /**
     * Table name to work with
     */
    const TABLE_NAME = "users";

    /**
     * Login user, return user data
     */
    public function login();
    /**
     * Login user from $_SESSION, return user data
     */
    public function loginBySession();
    /**
     * Validate user login, return boolean
     * @param string $login
     */
    public function validateLogin($login);
    /**
     * Return user data
     * @param int $id
     */
    public function getUserById($id);
    /**
     * Update user last activity field
     */
    public function updateLastActivity();
    /**
     * Get online users
     * @param int $chatId
     */
    public function getOnlineUsers($chatId);
    /**
     * Register new user
     */
    public function registerUser($login,$pass);
    /**
     * Get user data by Facebook ID
     * @param $id
     */
    public function getByFacebook($id);
    /**
     * Set user facebook id
     * @param $id
     */
    public function setFacebook($id);
    /**
     * Get user data by VK id
     * @param $id
     */
    public function getByVk($id);
    /**
     * Set user VK id
     * @param $id
     */
    public function setVk($id);
    /**
     * Get user data by google ID
     * @param $id
     */
    public function getByGoogle($id);
    /**
     * Set user google id
     * @param $id
     */
    public function setGoogle($id);
    /**
     * Set username (last name + first name)
     * @param $username
     */
    public function setUsername($username);
}
