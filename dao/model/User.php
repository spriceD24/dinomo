<?php
class User {
	public $userID;
	public $clientID;
	public $name;
	public $login;
	public $email;
	public $mobile;
	public $address;
	public $password;
	public $roles;
	public $deleteFlag;

	function __construct($userID, $clientID, $name, $login, $email, $mobile, $address, $roles) {
		$this->userID = $userID;
		$this->clientID = $clientID;
		$this->name = $name;
		$this->login = $login;
		$this->email = $email;
		$this->mobile = $mobile;
		$this->address = $address;
		if(!empty($roles))
		{
			$this->roles = explode ( '|', $roles );
		}else{
			$this->roles = array();
		}
	}
	
	function hasRole($role)
	{
		return in_array($role, $this->roles);
	}

}
?>
