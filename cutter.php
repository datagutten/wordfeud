<?Php
//X=vanrett
//Y=loddrett
//$infile='IMG_0211.PNG';


class cuttiles
{
	public $info;
	public $sizes;
	public $debug=false;
	private $information;
	public function __construct($im,$information)
	{

		$this->information=$information;
		$outdir="tiles/".$information->fileinfo['filename']."/";

		if(!file_exists($outdir) || $this->debug)
		{
			mkdir($outdir);
			
			
			foreach ($information->sizes->ylist as $ykey=>$y)
			{
				foreach($information->sizes->xlist as $xkey=>$x)
				{
					$tile=imagecreatetruecolor($information->sizes->tilesize,$information->sizes->tilesize); //En tile er symmetrisk og størrelsen er avhengig av enhet
	
					imagefill($tile,0,0,imagecolorallocate($tile,255,255,255)); //En tile skal ha hvit bakgrunn
					//       dst_im,src_im,dst_x,dst_y,src_x,src_y,src_w,src_h 
					imagecopy($tile,$im   ,0    ,0    ,$x   ,$y    ,640  ,640); //Hent en tile
					imagepng($tile,$outdir.$xkey."-".$ykey.'.png');
					imagedestroy($tile);
					/*echo $xlist+1;
					echo " - ";
					echo $xlist+42;
					echo "<br>";*/
				}
			}
			die();
			//header('Content-Type: image/png');
			//imagepng($tile);
			racktiles($input,$outdir,true); //Hent tiles på rack
			
			imagedestroy($im);
			imagedestroy($input);
			$folder=$outdir;
		}
		else
			die("$outdir eksisterer\n");
		return $outdir;
	}
	 //X=vanrett
	//Y=loddrett
	public function racktiles($image,$outdir,$resource=false)
	{
	
	imagepng($input,'test.png');
	$im=imagecreatetruecolor(640,90);
	//       dst_im,src_im,dst_x,dst_y,src_x,src_y,src_w,src_h 
	//imagecopy($rack,$this->information->
	imagecopy($im,$input,0,0,0,775,640,960); //Fjern topp og bunn
	imagepng($im,'rack.png');
	
	$tile=imagecreatetruecolor($size=42,42);
	echo $outdir."\n";
	for($i=0; $i<=6; $i++)
	{
	//       dst_im,src_im,dst_x,dst_y,src_x,src_y,src_w,src_h 
	//imagecopy($tiles,$im,95*$i,0,92*$i,0,90,90);
	//       dst_im,src_im,dst_x,dst_y,src_x,src_y,dst_w,dst_h,src_w,src_h
	imagecopyresampled($tile,$im,0,0,92*$i,0,42,42,90,90); //Kopier en tile og forminsk den til 42x42 piksler
	$rgb=imagecolorat($tile,1,1);
	
	
	if($rgb>0)
	{
	imagepng($tile,$outdir."rack_$i.png") or die("Feil ved oppretting av fil rack_$i.png\n"); //Skriv hver tile på racket til en fil
	$return[]="rack_$i.png";
	}
	//echo "<img src=\"tile_$i.png\" /><br>\n";	
	
	}
	
	return $return;
	//die();
}
	//include 'colorcheck.php';
}