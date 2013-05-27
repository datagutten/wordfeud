<?Php
require 'information/information.php';
require 'prepare/prepare.php';
require 'information/debug.php';

$imagefile=$argv[1];

if(!file_exists($imagefile))
	die("Finner ikke $imagefile\n");
$information=new information($imagefile);
$prepare=new prepare($information->folder);


//$information->debug=true;
//$prepare->debug=true;
//$debug=new debug;

$rack=$prepare->tilesplitter($information->inputim,$information->rackxlist,$information->sizes->racktilesize,$information->sizes->racky); //Hent spillerens brikker

if(isset($debug))
{
	$debug->displayimages($rack,'rack');
	echo '<br>';
}

$letters=array();
foreach($information->ylist as $ykey=>$y)
{
	$images=$prepare->tilesplitter($information->inputim,$information->xlist,$information->sizes->tilesize,$y); //Hent en rad med brikker
	if(isset($debug))
	{
		$debug->displayimages($images,$y);
		//$allimages[$y]=$images;
		echo '<br>';
	}
	$templetters=$prepare->sorttiles($images,$information->folder,$ykey);
	if($templetters!==false) //Bare legg til linjer som inneholder bokstaver
		$letters=$letters+$templetters;
}


if(isset($debug))
{
	$debug->displayimages($letters);
	echo '<br>';
}

include 'getblack.php';

foreach ($letters as $key=>$letter)
{
	list($blackletters[$key],$blankletters[$key])=getblack($letter);
	imagepng($blackletters[$key],$information->folder."letters_black/$key.png");
	$ocrletters[$key]=$prepare->ocr($information->folder."letters_black/$key.png",$information->folder."letters_ocr/$key");
}
if(isset($debug))
{
	$debug->displayimages($blackletters,'black',$ocrletters);
	var_dump($blankletters);
}
$tiles=$information->tiles;
	foreach ($ocrletters as $key=>$letter)
	{
		if(!$blankletters[$key])
			$tiles[$letter]--;
		else
			$tiles['blank']--;
	}
	print_r($tiles);