<?php
/**
 * Created by PhpStorm.
 * User: Harry
 * Date: 4/6/14
 * Time: 4:03 PM
 */

namespace Login\LoginBundle\Modals;


class Login {

    private $username;
    private $password;

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
} 