<?php
/**
 * logMsg function will log any error or info that might help in debugging.
 *
 * @param String $msg message which you want to log in logger
 * @param Integer $severity 0 or 1 if it's 1 then a mail will be sent when it is critical.
 * @param Integer $XMLStatus
 * @return void
 */
function logMsg($msg,$severity=0,$XMLStatus=0)
{
	$xml = makeErrorXML($msg,$severity,$XMLStatus);
	error_log($xml, 3, "log.txt");
	//echo $msg."\n";
}
/**
 * converts message into xml format with sever
 * @param String $msg message which you want to mail 
 * @param String $severity 0/1 1 will send mail.
 * @param String $XMLStatus 1 for root xml and 2 for end of xml tag
 *
 */
function makeErrorXML($msg, $severity=0, $XMLStatus)
{
	$xml="";
	if($XMLStatus==1)
		$xml.="<log>";
	
	$xml.="\n\t<logger_data>";
	$xml.="\n\t\t<severity>";
	$xml.=$severity;
	$xml.="</severity>";
	$xml.="\n\t\t<message>";
	$xml.=$msg;
	$xml.="</message>";
	$xml.="\n\t</logger_data>";
	
	if($XMLStatus==2)
		$xml.="\n</log>";
	
	return $xml;
}
?>