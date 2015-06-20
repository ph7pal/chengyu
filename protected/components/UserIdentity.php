<?php

class UserIdentity extends CUserIdentity {

    private $_id;

    public function authenticate() {
        $user = Users::model()->find('LOWER(username)=?', array(strtolower($this->username)));
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$this->validatePassword($user->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if ($user->status != Users::USER_PASSED)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else {
            $this->_id = $user->id;
            $this->username = $user->username;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function autoLogin($id,$username) {
        $this->_id = $id;
        $this->username = $username;
        $this->errorCode = self::ERROR_NONE;
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

    public function validatePassword($password) {
        return md5($this->password) == $password ? true : false;
    }

}
