<?php
require_once("../helper_function.php");
require_once("database/db_fetch.php");
session_start();
$return_array = array();
$user_id = $_SESSION["user_id"];
if(isset($user_id))
{
	$temp_array = db_select_sentbox($user_id);
	if($temp_array != -1 && $temp_array != -2)
	{
		$return_array = $temp_array;
	}
}
echo json_encode($return_array);
?>