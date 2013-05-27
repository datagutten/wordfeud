<?php
require("PHPMailer/class.phpmailer.php");
function sendmail($recipient,$subject,$body)
{
$mail = new PHPMailer();
require 'config.php';
$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = $smtp_server;
$mail->SMTPAuth = false;
$mail->Port = $smtp_port;
$mail->From = $from_address;
$mail->FromName = "Wordfeud analyzer";
$mail->AddAddress($recipient);

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML


	$mail->Subject = $subject;
	$mail->Body    = $body;

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   return false;
}
else
	return true;
}
