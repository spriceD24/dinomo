<?php
	class User
	{
		public $userID;
		public $name;
		public $email;
		public $mobile;
		public $address;
		public $password;
		public $roles;
	
		function __construct($userID, $name, $email, $mobile, $address)
		{
			$this->userID = $userID;
			$this->name = $name;
			$this->email = $email;
			$this->mobile = $mobile;
			$this->address = $address;
		}
	}
?>
