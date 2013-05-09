<?php
class information
{
	public $width;
	public $height;
	public $device;
	public $sizes;
	public $fileinfo;
	public $inputim;
	public $xlist;
	public $ylist;
	public $rackxlist;
	public $folder;
	public function __construct($imagefile)
	{
		$this->fileinfo=pathinfo($imagefile);
		$this->folder='output/'.$this->fileinfo['filename'].'/';
		if(!file_exists($imagefile))
			die("Finner ikke $imagefile");
		elseif($this->fileinfo['extension']!='png')
			die("Bildet må være i png format");
		
		$this->inputim=imagecreatefrompng($imagefile);
		$this->width=imagesx($this->inputim);
		$this->height=imagesy($this->inputim);
		$this->device=$this->selectdevice(array($this->width,$this->height)); //Finn enhetstype
		include 'sizes.php';
		$sizes=new $this->device($this->inputim); //Finn størrelsen utifra enhetstype
		$this->sizes=$sizes;

		$this->xlist=$this->tilelist($sizes->fromleft,$sizes->tilesize,$sizes->tilespacing,15); //Lag liste over alle kolonner
		$this->ylist=$this->tilelist($sizes->fromtop,$sizes->tilesize,$sizes->tilespacing,15); //Lag liste over alle rader
		$this->rackxlist=$this->tilelist($sizes->rackx,$sizes->racktilesize,$sizes->rackspacing,7); //Lag liste over hvor spillerens brikker befinner seg
	}
		
	private function selectdevice($size)
	{
		if($size[0]==768 && $size[1]=1024)
			$this->device='ipad2_large';
		elseif($size[0]==1136 && $size[1]==686)
			$this->device='bluestacks';
		elseif($size[0]==640 && $size[1]==960)
			$this->device='iphone4';
		elseif($size[0]==1536 && $size[1]==2048)
			$this->device='ipad3';
		else
			$this->device=false;
		return $this->device;
	}
	public function tilelist($startpos,$tilesize,$spacing,$positions) //Finn Y posisjonen for alle radene på spillebrettet
	{
		$ylist=array();
		$ypos=$startpos;
		for($i=1; $i<=$positions; $i++)
		{
			$ylist[]=$ypos;
			$ypos=$ypos+$tilesize+$spacing; //Neste posisjon er gjeldende posisjon + størrelsen på en brikke + avstanden mellom hver brikke
		}
		return $ylist;
	}
	/*public function racksplitter()
	{
		imagepng($this->rack,'rack.png');
		echo '<img src="rack.png" /><br />';
		$fromleft=1;
		for($i=0; $i<=6; $i++)
		{
			$tile=imagecreatetruecolor($this->sizes->racktilesize,$this->sizes->racktilesize);
			//echo $fromleft=($this->sizes->racktilesize+$this->sizes->rackspacing)*$i;
			echo $fromleft;
			echo "\n";
			imagecopy($tile,$this->rack,0,0,$fromleft,0,$this->sizes->racktilesize,$this->sizes->racktilesize);
			imagepng($tile,"tile_$i.png",9);

			echo "<img src=\"tile_$i.png\" />\n";
			$fromleft=$fromleft+$this->sizes->racktilesize+$this->sizes->rackspacing;
		}
	}*/

}
