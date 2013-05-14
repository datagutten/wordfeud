<?php
//$im=imagecreatefrompng('tiles_IMG_0187.PNG/letters/5-11.png');
//X=vanrett
//Y=loddrett
function getblack($im_in)
{
$im=imagecreatetruecolor(42,42);
imagecopyresampled($im,$im_in,0,0,0,0,42,42,imagesx($im_in),imagesy($im_in));
$tilesize=imagesx($im);

$letter=imagecreatetruecolor($tilesize,$tilesize);
$white=imagecolorallocate($letter,255,255,255);
imagefill($letter,0,0,$white);


$point=imagecreatetruecolor($tilesize,$tilesize); //Lag bilde for poenget
imagefill($point,0,0,imagecolorallocate($point,255,255,255)); //Fyll med farge
$isblank=true; //Opprett variabelen pointhit
for($y=2; $y<imagesy($im); $y++)
{
	for($x=2; $x<imagesx($im); $x++)
	{
		
			$rgb=imagecolorat($im,$x,$y);
	
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
		//echo "$x:$y - $rgb: $r, $g, $b<br>\n";
		if($rgb<=5300600) //5260600  5300600
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