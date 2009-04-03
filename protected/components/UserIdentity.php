<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

	const ERROR_EMAIL_INVALID=3;
	public $id;

	
	public function authenticate() {
		$record=User::model()->findByAttributes(array('username'=>$this->username));
		
		if ($record === null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} elseif ($record->password !== md5($this->password)) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
	//	} elseif ($record->email_confirmed != null) {
	//		$this->errorCode = self::ERROR_EMAIL_INVALID;
		} else {
			$this->username = $record->username;
			$this->id = $record->id;
			
			$this->setStates($record);
			
			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
	
	public function getId(){
		return $this->id;
	}

	private function setStates($user) {
	//	$this->setState('rank', $user->group_id);
		$this->setState('email',$user->email);
	}
}