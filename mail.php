<?Php session_start(); 
include 'dependcheck.php';
depend('munpack');
require 'config.php';
$headers = imap_headers($mbox);
include 'analyze.php';
include 'sendmail.php';

foreach($headers as $message=>$header)
{
	$message=$message+1;
	$header=imap_headerinfo($mbox,$message);
	preg_match('^\<(.*)\>^',$header->reply_toaddress,$result);
	$replyto=$result[1]; //Finn returadressen
	imap_savebody($mbox,'mail/body.eml',1);
	exec("munpack -C mail -fq body.eml",$output);
	unlink('mail/body.eml');
	print_r($output);
if($output[0]!='Did not find anything to unpack from body.eml') {
    foreach ($output as $attach) {
        $pieces = explode(" ", $attach);
		if(strpos(strtolower($pieces[0]),'.png')) //Sjekk at det er png bilde
		{
			unlink('mail/body.eml');
			$file="mail/".str_replace('@','_',$replyto)."--".$header->udate.".png";
			rename('mail/'.$pieces[0],$file);
			echo $file."<br>";
			break;
		}
			
        }
		$analysis=analyze($file);
		$send=sendmail($replyto,'Wordfeud analyze '.date('dmy H:i',$header->udate),$analysis);
		if($send)
			imap_delete($mbox,$message); //Slett mailen
		else
			echo 'Feil ved sending av melding'."\n";
			//var_dump($send);
		echo $analysis;

    }
	else
		imap_delete($mbox,$message); //Slett mailen



}
imap_close($mbox);
