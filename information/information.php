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
	public $tiles=array('A'=>7,'B'=>3,'C'=>1,'D'=>5,'E'=>9,'F'=>4,'G'=>4,'H'=>3,'I'=>6,'J'=>2,'K'=>4,'L'=>5,'M'=>3,'N'=>6,'O'=>4,'P'=>2,'R'=>7,'S'=>7,'T'=>7,'U'=>3,'V'=>3,'W'=>1,'Y'=>1,'Æ'=>1,'Ø'=>2,'Å'=>2,'blank'=>2);
	public function __construct($imagefile)
	{
		$this->fileinfo=pathinfo($imagefile);
		$this->folder='output/'.$this->fileinfo['filename'].'/';
		if(!file_exists($imagefile))
			die("Finner ikke $imagefile");

		if($this->fileinfo['extension']=='png')
			$this->inputim=imagecreatefrompng($imagefile);
		elseif($this->fileinfo['extension']=='jpg' || $this->fileinfo['extension']=='jpeg')
			$this->inputim=imagecreatefromjpeg($imagefile);
		else
			die("{$this->fileinfo['extension']} er ikke en støttet filtype");

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
			$this->device='ipad2_portrait';
		elseif($size[0]==1024 && $size[1]==768)
			$this->device='ipad2_landscape';
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

}
