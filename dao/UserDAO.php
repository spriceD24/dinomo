<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("util/DBUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/DateUtil.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php

class UserDAO {

	function getAllUsers() {
		$users = new Collection ();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$result = $conn->query("SELECT * FROM User where DeleteFlag = 0 ");
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$users->add ( new User($row["ID"],$row["ClientID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]));
			}
		}
		$conn->close();
		return $users;
	}

	function getAllUsersIncludingDeleted($clientID) 
	{
		$users = new Collection ();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$result = $conn->query("SELECT * FROM User where ClientID = ".$clientID." order by Name asc ");
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$user = new User($row["ID"],$row["ClientID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]);
				$user->deleteFlag = $row["DeleteFlag"];
				$users->add($user);
			}
		}
		$conn->close();
		return $users;
	}	
	
	function getUser($clientID,$id) {
		$user = new User(0, 0, "", "", "", "", "", "");
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$sql = "SELECT * FROM User where DeleteFlag = 0 and ID = ".$id." and ClientID = ".$clientID;
		$result = $conn->query($sql);
		LogUtil::debug ( 'UserDAO', ' get user sql = '.$sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$user = new User($row["ID"],$row["ClientID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]);
			}
		}
		$conn->close();
		
		return $user;
	}
	
	function getUserPass($userID) {
		// replace with call to DB
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT Password FROM User where DeleteFlag = 0 and ID = ".$userID);
		$password = "";
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) 
			{
				//return new User($row["ID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]);
				$password = StringUtils::decode($row["Password"]);
			}
		}
		$conn->close();
		return $password;
	}
	
	function isValidLogin($login, $password) {
		// replace with call to DB
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT Password FROM User where DeleteFlag = 0 and Login = '".StringUtils::escapeDB($login)."'");
		$res = false;
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) 
			{
				//return new User($row["ID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]);
				$res = (StringUtils::equals($password,StringUtils::decode($row["Password"])));
			}
		}
		$conn->close();
		return $res;		
	}
	
	function saveUser($insertedByID,$user)
	{
		LogUtil::debug ( 'UserDAO', 'Executing Save User');
		$nextUserID = $this->getNextUserID();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
		
		$sql = "insert into User(ID,ClientID,Name,Login,Password,Email,Mobile,Address,Roles,RecordDate,CreatedBy,LastUpdated,LastUpdatedBy,DeleteFlag) ";
		$sql = $sql." values (".$nextUserID.",".$user->clientID.",'".StringUtils::escapeDB($user->name)."','".StringUtils::escapeDB($user->login)."','".StringUtils::escapeDB(StringUtils::encode($user->password))."'";
		$sql = $sql.",'".StringUtils::escapeDB($user->email)."','".StringUtils::escapeDB($user->mobile)."','".StringUtils::escapeDB($user->address)."'";
		$sql = $sql.",'".implode("|",$user->roles)."',now(),".$insertedByID.",now(),".$insertedByID.",0) ";
		LogUtil::debug ( 'UserDAO', 'Saving user sql = '.$sql);
		//now()
		$ret = "";
		if ($conn->query($sql) === TRUE) {
			$ret =  "New record created successfully";
		} else {
			$ret =  "Error: " . $sql . "<br>" . $conn->error;
		}	
		$conn->close();
		return $nextUserID;
	}
	
	function updateUser($insertedByID,$user)
	{
		LogUtil::debug ( 'UserDAO', 'Executing Update User');
		$nextUserID = $this->getNextUserID();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
		
		$sql = "update User set Name = '".StringUtils::escapeDB($user->name)."', Login = '".StringUtils::escapeDB($user->login)."',Password = '".StringUtils::escapeDB(StringUtils::encode($user->password))."'";
		$sql = $sql.", Email = '".StringUtils::escapeDB($user->email)."', Mobile = '".StringUtils::escapeDB($user->mobile)."', Address = '".StringUtils::escapeDB($user->address)."'";
		$sql = $sql.", Roles = '".implode("|",$user->roles)."',LastUpdated = now(),LastUpdatedBy = ".$insertedByID;
		$sql = $sql." where ClientID = ".$user->clientID." and ID = ".$user->userID;
		
		LogUtil::debug ( 'UserDAO', 'Updating user sql = '.$sql);
		//now()
		$ret = "";
		if ($conn->query($sql) === TRUE) {
			$ret =  "Record updated successfully";
		} else {
			$ret =  "Error: " . $sql . "<br>" . $conn->error;
		}	
		$conn->close();
		return $ret;
	}
	
	private function getNextUserID()
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT MAX(ID) AS max_user FROM User");
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) 
			{
				$max = intval($row["max_user"])+1;
			}
		}
		$conn->close();
		return $max;
	}

	function deleteUser($userID,$deletedBy) {
		// replace with call to DB
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$conn->query("Update User set DeleteFlag = 1, LastUpdated =  now(), LastUpdatedBy =".$deletedBy." where ID = ".$userID);
		$conn->close();
		return true;
	}

	function activateUser($userID,$deletedBy) {
		// replace with call to DB
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$conn->query("Update User set DeleteFlag = 0, LastUpdated =  now(), LastUpdatedBy =".$deletedBy." where ID = ".$userID);
		$conn->close();
		return true;
	}
}

?>
