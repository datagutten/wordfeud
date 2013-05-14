<?Php
require 'information/information.php';
require 'prepare/prepare.php';
require 'information/debug.php';

if(!isset($argv[1]))
	$imagefile='information/sample.png';
else
	$imagefile=$argv[1];

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
	$ocrletters[$key]=$prepare->ocr($blackletters[$key],$information->folder."tekst/$key");
}
if(isset($debug))
{
	$debug->displayimages($blackletters,'black',$ocrletters);
	var_dump($blankletters);
}
include 'analyze.php';
print_r(analyze($ocrletters,$blankletters,$information->tiles));