<?php
	class User
	{
		public $userID;
		public $name;
		public $login;
		public $email;
		public $mobile;
		public $address;
		public $password;
		public $roles;
	
		function __construct($userID, $name, $login, $email, $mobile, $address)
		{
			$this->userID = $userID;
			$this->name = $name;
			$this->login = $login;
			$this->email = $email;
			$this->mobile = $mobile;
			$this->address = $address;
		}
	}
?>
