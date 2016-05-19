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
				if(StringUtils::equalsCaseInsensitive($user->email,$email))
				{
					return $user;
				}
			}
		}
		
		function getUserPass($userID)
		{
			return $this->userDAO->getUserPass($userID);
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
		
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<script type="text/javascript">
    parent.processForm('&ftpAction=openFolder');
</script>
</body>
</html>
