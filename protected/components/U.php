<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class U extends CUserIdentity {

    private $_id;
    public $email;

    public function __construct($email, $password) {
        parent::__construct($username, $password);
        $this->email = $email;
    }

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $validator = new CEmailValidator;
        if($validator->validateValue($this->email)){
            $user = Users::model()->find('email=:email', array(':email'=>  $this->email));
        }else{
            $user = Users::model()->find('truename=:truename', array(':truename'=>  $this->email));
        }
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if($user['status']!=Posts::STATUS_PASSED)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$this->validatePassword($user->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $user->id;
            $this->username = $user->truename;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }

    public function validatePassword($password) {
        //echo $password.'@####@'.$this->password;

        return md5($this->password) == $password ? true : false;
    }

}
