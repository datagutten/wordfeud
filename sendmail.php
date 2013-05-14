<?php
require("../../lib/PHPMailer_5.2.0/class.phpmailer.php");
function sendmail($recipient,$subject,$body)
{
$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "smtp.online.no";  // specify main and backup server
$mail->SMTPAuth = false;     // turn on SMTP authentication
$mail->Port = 587;
$mail->From = "wordfeud@datagutten.net";
$mail->FromName = "Wordfeud analyzer";
$mail->AddAddress($recipient);
//$mail->AddReplyTo("info@example.com", "Information");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML


	$mail->Subject = $subject;
	$mail->Body    = $body;

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   $return=false;
}
else
	$return=true;

return $return;
}