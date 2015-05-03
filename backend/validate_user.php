<?php
/**
 * validate_user.php requires two post arguments email_id and pass
 */
require_once("../helper_function.php");
require_once("database/db_fetch.php");
//set default values
$valid = 0;
$message = '';
//$email_id = 'kshah215@gmail.com';
//$password = '02091993';
$email_id = $_POST["email_id"];
$password = $_POST["pass"];
//move ahead only if the variables are set
if(isset($email_id) and isset($password))
{
	//get role for user-pass
	$valid = db_validate_user($email_id, $password);
	//for correct credential
	if($valid == 1)
	{
		$message = 'successfull';
		session_start();
		$_SESSION["user_id"] = db_fetch_user_id($email_id);
		logMsg("user $email_id logged in");
	}
	else
	{
		//send a message if the validation fails
		if($valid == 0)
			$message = 'internal error occurred during executing query';
		else if($valid == -1)
			$message = 'user not found';
		else if($valid == -2)
			$message = 'password do not match';
	}
}
echo json_encode(array($valid, $message));
?>