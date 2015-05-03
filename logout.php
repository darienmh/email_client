<?php
	require_once("helper_function.php");
	logMsg("user ".$_SESSION["user_id"]." logged out successfully");
	session_start();
	session_destroy(); //destroy the session
	header("location: index.php"); //to redirect back to "index.php" after logging out
	exit();
?>