<?Php
//Forberedelser f�r analysering
class prepare
{
	public $debug=false;
	function __construct($folder)
	{
		$this->makefolder($folder);
	}
	
	private function makefolder($folder)
	{
		if(!file_exists($folder))
			mkdir($folder);
		if(!file_exists($folder.'letters'))
		{
			//Lag mapper
			mkdir($folder.'TW');
			mkdir($folder.'black_slots');
			mkdir($folder.'TL');
			mkdir($folder.'DL');
			mkdir($folder.'DW');
			mkdir($folder.'letters');
			mkdir($folder.'tekst');
		}
	}
	public function tilesplitter($im,$tilelist,$tilesize,$fromtop)
	{
		foreach ($tilelist as $i=>$fromleft)
		{
			$tileimages[$i]=imagecreatetruecolor($tilesize,$tilesize);
			imagecopy($tileimages[$i],$im,0,0,$fromleft,$fromtop,$tilesize,$tilesize);
			//$fromleft=$fromleft+$tilesize+$tilespace;
		}
		return $tileimages;
	}

	private $colorlimits=array('black_slots'=>array(2600000,2800000),
							'DW'=>array(0xB07010,0xB77320), //Oppdatert 080513
							'TW'=>array(0x793539,0x843d41), //Oppdatert 080513
							'DL'=>array(0x709865,0x73A960), //B�r fungere
							'TL'=>array(0x4660a0,0x4761a5), //Oppdatert 080513
							'letters'=>array(0,0));

	
	public function sorttiles($images,$folder,$y)
	{	
		foreach ($images as $x=>$im)
		{
			//Finn fargen p� posisjon 6,6	

			$color=imagecolorat($im,6,6);				
			//imagedestroy($im);

			foreach($this->colorlimits as $field=>$limit)
			{
				if(($color>=$limit[0] && $color<=$limit[1]) || $field=='letters')
				{
					if($this->debug)
						echo "$x-$y: $field ".dechex($color)."\n";
				
					imagepng($im,$folder.$field."/$x-$y.png");
					//copy($folder.$file,$folder.$field.'/'.$file);
					if($field=='letters')
						$letters["$x-$y"]=$im;
					continue 2; //Hopp til neste brikke
				}

			}
		}
		if(!isset($letters))
			$letters=false; //Ingen bokstaver funnet p� raden
		
		return $letters;
	}
	public function ocr($im,$outfile)
	{
		imagepng($im,$image='/tmp/image.png');
		shell_exec("tesseract \"$image\" \"$outfile\" -psm 10 -l nor");
		unlink('/tmp/image.png');
		$text=trim(file_get_contents($outfile.'.txt'));
		$text=str_replace(array('0'),array('O'),$text,$replacements);
	
		return $text;
	}
	
}
