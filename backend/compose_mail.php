<?php
require_once("../helper_function.php");
require_once("database/db_fetch.php");
session_start();
$content = isset($_POST["content"])?$_POST["content"]:$all_set = 0;
$to_email_id = isset($_POST["to_email_id"])?$_POST["to_email_id"]:$all_set = 0;
$cc_email_id = isset($_POST["cc_email_id"])?$_POST["cc_email_id"]:$all_set = 0;
$bcc_email_id = isset($_POST["bcc_email_id"])?$_POST["bcc_email_id"]:$all_set = 0;
$new_thread = isset($_POST["new_thread"])?$_POST["new_thread"]:$all_set = 0;
$subject = $_POST["subject"];;
$thread_id = $_POST["thread_id"];
$user_id = $_SESSION["user_id"];
$successful = 0;
if(isset($user_id))
{
	$successful = db_compose_mail($user_id, $content, $to_email_id, $cc_email_id, $bcc_email_id, $new_thread , $subject, $thread_id);
}
echo $successful;
?>