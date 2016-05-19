<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php

	class UserDAO
	{
		function getAllUsers()
		{
			$users = new Collection;
			
			$users->add(new User(1,'Stephen Price','sprice','sprice_D24@yahoo.com','343433440450',''));
			$users->add(new User(2,'Patrick Noonan','pnoonan','patricknoonan@dinomoformwork.com.au','0303040450',''));
			$users->add(new User(3,'John Smith','jsmith','john@johnsite.com','3434343',''));
			
			return $users;
		}
		
		function isValidLogin($login,$password)
		{
			//TODO add DB query
			return ($login == $password);
		
		}		
	}

?>
