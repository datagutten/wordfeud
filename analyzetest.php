<?Php
require 'information/information.php';
require 'prepare/prepare.php';
require 'information/debug.php';

//$prepare->debug=true;
//$debug=new debug;



//$imagefile='information/sizes/samples/ipad3.png';
$imagefile='information/sizes/samples/iphone4.png';

$information=new information($imagefile);
$prepare=new prepare($information->folder);
print_r($information);
//$information->debug=true;

$rack=$prepare->tilesplitter($information->inputim,$information->rackxlist,$information->sizes->racktilesize,$information->sizes->racky); //Hent spillerens brikker

if(isset($debug))
{
	$debug->displayimages($rack,'rack');
	echo '<br>';
}

//$information->ylist=$prepare->tilelist($information->sizes->fromtop,$information->sizes->tilesize,$information->sizes->tilespacing,15); //Lag liste over alle rader
//$information->xlist=$prepare->tilelist($information->sizes->fromleft,$information->sizes->tilesize,$information->sizes->tilespacing,15); //Lag liste over alle kolonner


$letters=array();
foreach($information->ylist as $ykey=>$y)
{

	$images=$prepare->tilesplitter($information->inputim,$information->xlist,$information->sizes->tilesize,$y); //936y
	if(isset($debug))
	{
		$debug->displayimages($images,$y);
		//$allimages[$y]=$images;
		echo '<br>';
	}
	$templetters=$prepare->sorttiles($images,$information->folder,$ykey);
	if($templetters!==false)
		$letters=$letters+$templetters;
}

$debug=new debug;
if(isset($debug))
	$debug->displayimages($letters);
		echo '<br>';

include 'getblack.php';

foreach ($letters as $key=>$letter)
{
	echo $prepare->ocr($blackletters[$key]=getblack($letter),$information->folder."tekst/$key")."<br>\n";
}
if(isset($debug))
	$debug->displayimages($blackletters,'black');
