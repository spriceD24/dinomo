<?php include_once("dao/model/User.php"); ?>
<?php include_once("dao/UserDAO.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/CacheUtil.php"); ?>
<?php

class UserDelegate {
	private $userDAO;
	function __construct() {
		$this->userDAO = new UserDAO ();
	}
	function getUser($userID) {
		$allUsers = $this->getAllUsers ();
		while ( $user = $allUsers->iterate () ) {
			if ($user->userID == $userID) {
				return $user;
			}
		}
	}
	function getUserByLogin($login) {
		$login = strtolower ( $login );
		$allUsers = $this->getAllUsers ();
		LogUtil::debug ( "UserDelegate", "looking for user - " . $login );
		while ( $user = $allUsers->iterate () ) {
			LogUtil::debug ( "UserDelegate", "checking against user - " . $user->login );
			if (StringUtils::equalsCaseInsensitive ( $user->login, $login )) {
				return $user;
			}
		}
		LogUtil::debug ( "UserDelegate", "No match found" );
	}
	
	function getUserByEmail($email) {
		$email = strtolower ( $email );
		$allUsers = $this->getAllUsers ();
		while ( $user = $allUsers->iterate () ) {
			if (StringUtils::equalsCaseInsensitive ( $user->email, $email )) {
				return $user;
			}
		}
	}
	
	function getAllUsersForClient($clientID) 
	{
		$clientUsers = new Collection();
		$allUsers = $this->getAllUsers ();
		while ( $user = $allUsers->iterate () ) {
			if($user->clientID == $clientID)
			{
				$clientUsers->add($user);
			}
		}
		return $clientUsers;
	}
	
	function getUserPass($userID) {
		return $this->userDAO->getUserPass ( $userID );
	}
	function getAllUsers() 
	{
		$users = CacheUtil::getCachedUsers();
		if(empty($users))
		{
			LogUtil::debug ( 'UserDelegate', 'Loading users from DB' );
			$users = $this->userDAO->getAllUsers ();
			CacheUtil::addCachedUsers($users);
		}else{
			LogUtil::debug ( 'UserDelegate', 'Loading users from Cache' );
		}
		LogUtil::debug ( 'UserDelegate', 'Number of users = '.$users->getNumObjects() );
			
		return $users;
	}
	
	function isValidLogin($login, $password) {
		return $this->userDAO->isValidLogin ( strtolower ( $login ), strtolower ( $password ) );
	}
	
	function saveUser($insertedByID,$user)
	{
		$ret = $this->userDAO->saveUser($insertedByID, $user);
		CacheUtil::removeCachedUsers();
		return $ret;
	}
	
	function updateUser($insertedByID,$user)
	{
		$ret = $this->userDAO->updateUser($insertedByID, $user);
		CacheUtil::removeCachedUsers();
		return $ret;
	}
	
	function deleteUser($userID,$deletedBy)
	{
		$ret = $this->userDAO->deleteUser($userID, $deletedBy);
		CacheUtil::removeCachedUsers();
		return $ret;
	}
	
	function activateUser($userID,$deletedBy)
	{
		$ret = $this->userDAO->activateUser($userID, $deletedBy);
		CacheUtil::removeCachedUsers();
		return $ret;
	}
	
	function getUserForClient($clientID,$id) 
	{
		return $this->userDAO->getUser($clientID,$id);
	}
	
	function getAllUsersIncludingDeleted($clientID) 	
	{
		$allUsers = $this->userDAO->getAllUsersIncludingDeleted($clientID);	
		$usersMap = array();
		while ( $user = $allUsers->iterate () ) 
		{
			$usersMap[$user->userID] = $user;
		}
		return $usersMap;
	}
}

?>
