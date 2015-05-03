<?php
require_once('class.Database.inc.php');
/**
 * db_validate_user it compares username and password from gmail.
 * @param string $email_id email_id
 * @param string $pass password
 * @return int if the user credential are correct or not
 *       1 if user credential match
 *		-1 if user not found
 *		-2 if password not match
 *       0 sql could not be run
 */
function db_validate_user($email_id, $pass, $db_index = 0)
{
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$table = 'user';
	$error_found = 1;
	$query = "SELECT user_id, password FROM `$table` where `email_id`  = '$email_id'";
	if($result = $mysqli->query($query))
	{
       if($row = $result->fetch_assoc())
	   {
           $password = $row['password'];
           if($password == $pass)
           {
           		$error_found = 1;
           }
           else
           {
               	//password do not match
               	$error_found = -2;
           }
       }
       else
       {
			//user not found.
			$error_found = -1;
       }
	}
	else
	{
		//could not run sql query.
		$error_found = 0;
	}
	return $error_found;
}
/**
 * db_fetch_user_id fetches user_id for an email id
 * @param string $email_id email_id
 * @return int user_id
 */
function db_fetch_user_id($email_id,$db_index = 0)
{
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$table = 'user';
	$user_id = -1;
	$query = "SELECT user_id FROM `$table` where `email_id`  = '$email_id'";
	if($result = $mysqli->query($query))
	{
       if($row = $result->fetch_assoc())
	   {
           $user_id = $row['user_id'];
       }
       else
       {
			//email not found.
			$user_id = -2;
       }
	}
	else
	{
		//could not run sql query.
		$user_id = -1;
	}
	return $user_id;
}
/**
 * db_fetch_email_id fetches email id for a user_id
 * @param string $user_id user_id
 * @return string email_id
 */
function db_fetch_email_id($user_id,$db_index = 0)
{
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$table = 'user';
	$email_id = -1;
	$query = "SELECT email_id FROM `$table` where `user_id`  = '$user_id'";
	if($result = $mysqli->query($query))
	{
       if($row = $result->fetch_assoc())
	   {
           $email_id = $row['email_id'];
       }
       else
       {
			//email not found.
			$email_id = -2;
       }
	}
	else
	{
		//could not run sql query.
		$email_id = -1;
	}
	return $email_id;
}
/**
 * this function returns thread_id, email_id, subject  of thread  for user_id 
 * -1 if error occurred
 *	-2 if inbox is empty
 * array if non empty inbox
 */
function db_select_inbox($user_id, $db_index = 0)
{
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$return_arr = array();
	$query = "SELECT `fk_thread_id`, `user`.`email_id`, `thread`.`subject` FROM `user_mail_sent_mapping` INNER JOIN `mail` ON (`user_mail_sent_mapping`.`fk_mail_id`=`mail`.`mail_id`) INNER JOIN `thread` ON (`thread`.`thread_id`=`mail`.`fk_thread_id`) INNER JOIN `user` ON (`user`.`user_id`=`mail`.`fk_user_id`)WHERE `user_mail_sent_mapping`.`fk_user_id` = '$user_id'";
	if($result = $mysqli->query($query))
	{
       while($row = $result->fetch_assoc())
	   {
	   	array_push($return_arr, $row);
       }
       if(empty($return_arr))
       {
			//email not found.
			$return_arr = -2;
       }
	}
	else
	{
		//could not run sql query.
		$return_arr = -1;
	}
	return $return_arr;
}
/**
 * this function returns thread_id, email_id, subject  of thread  for user_id 
 * -1 if error occurred
 *	-2 if inbox is empty
 * array if non empty inbox
 */
function db_select_sentbox($user_id, $db_index = 0)
{
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$return_arr = array();
	$query = "SELECT `fk_thread_id`, `user`.`email_id`, `thread`.`subject` FROM `mail` INNER JOIN `thread` ON (`thread`.`thread_id`=`mail`.`fk_thread_id`) INNER JOIN `user` ON (`user`.`user_id`=`mail`.`fk_user_id`) WHERE `mail`.`fk_user_id` = '$user_id'";
	if($result = $mysqli->query($query))
	{
       while($row = $result->fetch_assoc())
	   {
	   	array_push($return_arr, $row);
       }
       if(empty($return_arr))
       {
			//email not found.
			$return_arr = -2;
       }
	}
	else
	{
		//could not run sql query.
		$return_arr = -1;
	}
	return $return_arr;
}
function db_insert_thread($subject, $db_index = 0)
{
	logMsg("entered function db_insert_thread() with subject $subject db_index $db_index");
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$table = 'thread';
	$thread_id = NULL;
	$query = "INSERT into `$table`(`subject`) values('".$subject."')";
	if($result = $mysqli->query($query))
	{
		$thread_id = $mysqli->insert_id;
		logMsg("thread added to table $table with thread_id $thread_id \n");
	}
	else
	{
		logMsg("sql query to insert values in $table have problem with subject $subject could not execute query $query with error ".$mysqli->error,1);
		$thread_id = -1;
	}
	logMsg("exiting function db_insert_thread() with inserted thread_id $thread_id");
	return $thread_id;
}
function db_insert_mail($fk_thread_id, $fk_user_id, $content, $db_index = 0)
{
	logMsg("entered function db_insert_mail() with fk_thread_id $fk_thread_id, fk_user_id $fk_user_id, content $content");
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$table = 'mail';
	$mail_id = NULL;
	$query = "INSERT into `$table`(`fk_thread_id`, `fk_user_id`, `content`) values('".$fk_thread_id."', '".$fk_user_id."', '".$content."')";
	if($result = $mysqli->query($query))
	{
		$mail_id = $mysqli->insert_id;
		logMsg("thread added to table $table with mail_id $mail_id \n");
	}
	else
	{
		logMsg("sql query to insert values in $table have problem with subject $subject could not execute query $query with error ".$mysqli->error,1);
		$mail_id = -1;
	}
	logMsg("exiting function db_insert_mail() with inserted mail_id $mail_id");
	return $mail_id;
}
function db_insert_user_mail_sent_mapping($fk_mail_id, $fk_user_id, $is_cc, $is_bcc , $db_index = 0)
{
	logMsg("entered function db_insert_user_mail_sent_mapping() with fk_mail_id $fk_mail_id, fk_user_id $fk_user_id, is_cc $is_cc, is_bcc $is_bcc");
	$db = Database::getInstance("gmail", $db_index);
    $mysqli = $db->getConnection();
	$table = 'user_mail_sent_mapping';
	$successful = 0;
	$query = "INSERT into `$table`(`fk_mail_id`, `fk_user_id`,  `is_cc`, `is_bcc`) values('".$fk_mail_id."', '".$fk_user_id."', '".$is_cc."', '".$is_bcc."')";
	if($result = $mysqli->query($query))
	{
		logMsg("mapping added to table $table with fk_mail_id $fk_mail_id and fk_user_id $fk_user_id \n");
		$successful = 1;
	}
	else
	{
		logMsg("sql query to insert values in $table have problem, could not execute query $query with error ".$mysqli->error,1);
		$successful = 0;
	}
	logMsg("exiting function db_insert_user_mail_sent_mapping() with successful $successful");
	return $successful;
}
function insert_sent_mapping($fk_mail_id, $email_id_comma_separated, $is_cc, $is_bcc)
{
	$email_id_array = explode(",", $email_id_comma_separated);
	foreach ($email_id_array as $key => $email_id)
	{
		$fk_user_id = db_fetch_user_id($email_id);
		//check that email id exists in account
		if($fk_user_id != -1 && $fk_user_id != -2)
		{
			$successful = db_insert_user_mail_sent_mapping($fk_mail_id, $fk_user_id, $is_cc, $is_bcc);
			logMsg("inserted mapping with successful $successful");
		}
	}
}
function db_compose_mail($user_id, $content, $to_email_id, $cc_email_id, $bcc_email_id, $new_thread , $subject, $thread_id)
{
	$successful = 1;
	//if new_thread =1 insert int thread
	if($new_thread == 1)
	{
		$thread_id = db_insert_thread($subject);
	}
	if($thread_id != -1)
	{
		//insert into mail
		$mail_id = db_insert_mail($thread_id, $user_id, $content);
		if($mail_id != -1)
		{
			//for each of sent ,cc, bcc insert into mapping
			insert_sent_mapping($mail_id, $to_email_id, 0, 0);
			insert_sent_mapping($mail_id, $cc_email_id, 1, 0);
			insert_sent_mapping($mail_id, $bcc_email_id, 0, 1);
		}
		else
		{
			$successful &= 0;
		}
	}
	else
	{
		$successful &= 0;
	}
	return $successful;
}
?>