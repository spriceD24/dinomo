<?php include_once("dao/model/User.php"); ?>
<?php include_once("dao/UserDAO.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php

	class UserDelegate
	{
		private $userDAO;
		
		function __construct()
		{
			$this->userDAO = new UserDAO();
		}
			
		function getUser($userID)
		{
			$allUsers = $this->getAllUsers();
			while ( $user = $allUsers->iterate () )
			{
				if($user->userID == $userID)
				{
					return $user;
				}
			}
		}

		function getUserByLogin($login)
		{
			$login = strtolower($login);
			$allUsers = $this->getAllUsers();
			LogUtil::debug("UserDelegate","looking for user - ".$login);
			while ( $user = $allUsers->iterate () )
			{
				LogUtil::debug("UserDelegate","checking against user - ".$user->login);
				if(StringUtils::equalsCaseInsensitive($user->login,$login))
				{
					return $user;
				}
			}
			LogUtil::debug("UserDelegate","No match found");
		}	
		
		function getUserByEmail($email)
		{
			$email = strtolower($email);
			$allUsers = $this->getAllUsers();
			while ( $user = $allUsers->iterate () )
			{
				if($user->email == $email)
				{
					return $user;
				}
			}
		}
		
		function getAllUsers()
		{
			return $this->userDAO->getAllUsers();
		}
		
		function isValidLogin($login,$password)
		{
			return $this->userDAO->isValidLogin(strtolower($login), strtolower($password));
		}
		
	}
		
?>