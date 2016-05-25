<?php
class User {
	public $userID;
	public $name;
	public $login;
	public $email;
	public $mobile;
	public $address;
	public $password;
	public $roles;
	
	function __construct($userID, $name, $login, $email, $mobile, $address, $roles) {
		$this->userID = $userID;
		$this->name = $name;
		$this->login = $login;
		$this->email = $email;
		$this->mobile = $mobile;
		$this->address = $address;
		if(!empty(roles))
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
