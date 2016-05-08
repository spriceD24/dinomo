<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php

	class UserDAO
	{
		function getUser($userID)
		{
			$user = new User(2,'Pat Noonan','patricknoonan@dinomoformwork.com.au','0303040450','');
			return $user;
		}
		
	
		function getAllUsers()
		{
			$users = new Collection;
			
			$users->add(new User(1,'Stephen Price','sprice_D24@yahoo.com','343433440450',''));
			$users->add(new User(2,'Pat Noonan','patricknoonan@dinomoformwork.com.au','0303040450',''));
			$users->add(new User(3,'John Smith','john@johnsite.com','3434343',''));
			
			return $users;
		}
	}

?>
