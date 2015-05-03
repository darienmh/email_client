<?php
require_once("../helper_function.php");
require_once("database/db_fetch.php");
session_start();
$return_array = array();
$user_id = $_SESSION["user_id"];
//$user_id = 2;
if(isset($user_id))
{
	$temp_array = db_select_inbox($user_id);
	if($temp_array != -1 && $temp_array != -2)
	{
		$return_array = $temp_array;
	}
}
echo json_encode($return_array);
?>