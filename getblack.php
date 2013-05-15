<?php
//$im=imagecreatefrompng('tiles_IMG_0187.PNG/letters/5-11.png');
//X=vanrett
//Y=loddrett
function getblack($im_in)
{
	$tilesize=42; //Så lenge det brukes en fast posisjon for bokstaven må brikken alltid ha samme størrelse
	$im=imagecreatetruecolor($tilesize,$tilesize); //Lag bilde for den skalerte brikken
	imagecopyresampled($im,$im_in,0,0,0,0,$tilesize,$tilesize,imagesx($im_in),imagesy($im_in)); //Skaler brikken
	
	$letter=imagecreatetruecolor($tilesize,$tilesize);
	imagefill($letter,0,0,imagecolorallocate($letter,255,255,255)); //Fyll bildet for bokstaven med hvitt
	$point=imagecreatetruecolor($tilesize,$tilesize); //Lag bilde for poenget
	imagecopy($point,$letter,0,0,0,0,$tilesize,$tilesize); //Kopier den hvite bakgrunnen fra $letter
	
	$isblank=true; //Opprett variabelen pointhit
	for($y=2; $y<imagesy($im); $y++)
	{
		for($x=2; $x<imagesx($im); $x++)
		{
			if(imagecolorat($im,$x,$y)<=0x50E178) //Sjekk om fargen på gjeldende piksel er svart (desimal 5300600)
			{
				//       dst_im, src_im,dst_x,dst_y,src_x,src_y,src_w,src_h 
				if($y<=40 && $x<=27) //Hent bokstaven uten poeng
					imagecopy($letter,$im   ,$x    ,$y    ,$x   ,$y    ,1  ,1);
				elseif($y<=30 && $x<=38) //Sjekk om bokstaven gir poeng
				{
					imagecopy($point,$im   ,$x    ,$y    ,$x   ,$y    ,1  ,1);
					$isblank=false;
				}
			}
		}
	}
	
	return array($letter,$isblank);
}
?>