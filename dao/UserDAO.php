<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php

class UserDAO {
	function getAllUsers() {
		$users = new Collection ();
		
		$users->add ( new User ( 1, 'Stephen Price', 'sprice', 'stefdogd24@gmail.com', '343433440450', '','recipient' ) );
		$users->add ( new User ( 2, 'Patrick Noonan', 'pnoonan', 'patricknoonan@dinomoformwork.com.au', '0303040450', '','user' ) );
		$users->add ( new User ( 3, 'Terence McGarrigle', 'terry', 'terrymc_garrigle@hotmail.com', '0303040450', '','user' ) );
		$users->add ( new User ( 4, 'John Smith', 'jsmith', 'john@johnsite.com', '3434343','', 'user' ) );
		
		return $users;
	}
	function getUserPass($userID) {
		// replace with call to DB
		$allUsers = $this->getAllUsers ();
		while ( $user = $allUsers->iterate () ) {
			if ($user->userID == $userID) {
				return $user->login;
			}
		}
	}
	function isValidLogin($login, $password) {
		// TODO add DB query
		return ($login == $password);
	}
}

?>
