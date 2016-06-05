<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("util/DBUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/DateUtil.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php

class UserDAO {
	function getAllUsersFixed() {
		$users = new Collection ();
		
		$users->add ( new User ( 1, 'Stephen Price', 'sprice', 'stephen.price@credit-suisse.com', '+61424185814', '','admin|user' ) );
		$users->add ( new User ( 2, 'Patrick Noonan', 'pnoonan', 'patricknoonan@dinomoformwork.com.au', '+61433965870', '','user' ) );
		$users->add ( new User ( 3, 'John Difrancesco', 'johndi', 'johndi@dinomoformwork.com.au', '+61400945426', '','user' ) );
		$users->add ( new User ( 4, 'Dominic Difrancesco', 'dominic', 'dominic@dinomoformwork.com.au', '','', 'user' ) );
		
		return $users;
	}

	function getAllUsers() {
		$users = new Collection ();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT * FROM User where DeleteFlag = 0 ");
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$users->add ( new User($row["ID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]));
			}
		}
		return $users;
	}	

	function getUserPass($userID) {
		// replace with call to DB
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT Password FROM User where DeleteFlag = 0 and ID = ".$userID);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) 
			{
				//return new User($row["ID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]);
				return StringUtils::decode($row["Password"]);
			}
		}
		return "";
	}
	
	function isValidLogin($login, $password) {
		// replace with call to DB
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT Password FROM User where DeleteFlag = 0 and Login = '".StringUtils::escapeDB($login)."'");
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) 
			{
				//return new User($row["ID"],$row["Name"],$row["Login"],$row["Email"],$row["Mobile"],$row["Address"],$row["Roles"]);
				return $password = StringUtils::decode($row["Password"]);
			}
		}
		return false;		
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
		
		$sql = "insert into User(ID,Name,Login,Password,Email,Mobile,Address,Roles,RecordDate,CreatedBy,LastUpdated,LastUpdatedBy,DeleteFlag) ";
		$sql = $sql." values (".$nextUserID.",'".StringUtils::escapeDB($user->name)."','".StringUtils::escapeDB($user->login)."','".StringUtils::escapeDB(StringUtils::encode($user->password))."'";
		$sql = $sql.",'".StringUtils::escapeDB($user->email)."','".StringUtils::escapeDB($user->mobile)."','".StringUtils::escapeDB($user->address)."'";
		$sql = $sql.",'".implode("|",$user->roles)."',now(),".$insertedByID.",now(),".$insertedByID.",0) ";
		LogUtil::debug ( 'UserDAO', 'Saving user sql = '.$sql);
		//now()
		if ($conn->query($sql) === TRUE) {
			return "New record created successfully";
		} else {
			return "Error: " . $sql . "<br>" . $conn->error;
		}		
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
				return intval($row["max_user"])+1;
			}
		}
		return $max;
	}

	function deleteUser($userID) {
		// replace with call to DB
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$conn->query("Update User set DeleteFlag = 1 where ID = ".$userID);
		return true;
	}
}

?>
