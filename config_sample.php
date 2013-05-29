<?Php
$imap_server="imap.gmail.com:993";
$imap_user="yourmail@gmail.com";
$imap_password="yourpassword";

//Parametere for PHPMailer
$mail->Host = "smtp.yourmail.no";
$mail->SMTPAuth = false;
$mail->Port = 25;
$mail->From = "yourmail@gmail.com";

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->FromName = "Wordfeud analyzer";
$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML
?>