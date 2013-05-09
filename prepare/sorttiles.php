<?Php
//erstatter colorcheck.php
//lagt inn i prepare.php
die('Bruk prepare.php');
class prepare
{
	public $debug=false;
	public $nodelete=false;
	public $nocopy=false;
	private $colorlimits=array('black_slots'=>array(2600000,2800000),
							'DW'=>array(0xB07010,0xB77320), //Oppdatert 080513
							'TW'=>array(0x793539,0x843d41), //Oppdatert 080513
							'DL'=>array(0x709865,0x73A960), //Bør fungere
							'TL'=>array(0x4660a0,0x4761a5), //Oppdatert 080513
							'letters'=>array(0,0));
	public function sorttiles($folder)
	{	
        $dir = array_diff(scandir($folder),array(".", "..","Thumbs.db"));
		
		if(!file_exists($folder.'letters'))
		{
			//Lag mapper
			mkdir($folder.'TW');
			mkdir($folder.'black_slots');
			mkdir($folder.'TL');
			mkdir($folder.'DL');
			mkdir($folder.'DW');
			mkdir($folder.'letters');
	
			foreach ($dir as $file)
			{
				if(is_dir($file))
					continue;
				//Finn fargen på posisjon 6,6	
				$im=imagecreatefrompng($folder.$file);
				$color=imagecolorat($im,6,6);				
				imagedestroy($im);
	
				foreach($this->colorlimits as $field=>$limit)
				{
					if(($color>=$limit[0] && $color<=$limit[1]) || $field=='letters')
					{
						if($this->debug)
							echo "$file: $field ".dechex($color)."\n";
						if(!$this->nocopy)
							copy($folder.$file,$folder.$field.'/'.$file);
						if(!$this->nodelete)
							unlink($folder.$file);
						
						continue 2; //Hopp til neste brikke
					}
	
				}
			}
		}
	}

}