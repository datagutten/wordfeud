<?Php
//Script som henter bilder av spill fra en gmailadresse og analyserer de

require 'tools/dependcheck.php';
$dependcheck=new dependcheck;
if($dependcheck->depend('munpack')!==true)
	die("munpack er nødvendig for å hente vedlegg fra mailer\n");
require "PHPMailer/class.phpmailer.php";
$mail = new PHPMailer();
require 'config.php';
require 'analyze.php';

if(!file_exists('mail')) //Lag mappe for mottatte mailer
	mkdir('mail');

$mbox = imap_open($server='{'.$imap_server.'/imap/ssl}INBOX', $imap_user,$imap_password); //Åpne mailboksen
$headers = imap_headers($mbox);


foreach($headers as $message=>$header)
{
	$message++;
	$header=imap_headerinfo($mbox,$message);
	//print_r($header);

	//die();
	$folder=$header->from[0]->mailbox.'_'.$header->from[0]->host.'_'.$header->udate; //Lag mappenavn
	mkdir('mail/'.$folder); //Opprett mappe
	preg_match('^\<(.*)\>^',$header->reply_toaddress,$result);
	$replyto=$result[1]; //Finn returadressen
	imap_savebody($mbox,$eml="mail/$folder/body.eml",1);

	exec("munpack -C mail/$folder -fq body.eml 2>&1",$output); //filnavnet i siste argument er relativt til banen i første argument
	unlink($eml);

if($output[0]!='Did not find anything to unpack from body.eml')
{
    foreach ($output as $attach)
	{
		if(preg_match('^(.+) \(image/([a-z]+)\)^',$attach,$fileinfo)) //Sjekk at det er bilde vedlagt
		{
			//$file="mail/".str_replace('@','_',$replyto)."--".$header->udate.".png";
			$file="mail/$folder/$folder.png";
			rename("mail/$folder/{$fileinfo[1]}",$file);
			echo $file."<br>";
			break;
		}	
	}

	$mail->Subject='Wordfeud analyze '.date('dmy H:i',$header->udate);
	$mail->Body=analyze($file); //Analyser spillet og bruk resultatet som tekst i mailen
	$mail->AddAddress($replyto); //Send svar til oppgitt svaradresse

	if(!$mail->Send())
		echo "Feil ved sending av melding: {$mail->ErrorInfo}\n";
	else
	{
		echo "Meldingen ble sendt\n";
		imap_delete($mbox,$message); //Slett mailen
	}
}
else
	imap_delete($mbox,$message); //Slett mailen



}
imap_close($mbox);
