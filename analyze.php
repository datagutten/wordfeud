<?Php
function analyze($imagefile)
{
	
	if(!file_exists($imagefile))
		die("Finner ikke $imagefile\n");
	require 'information/information.php';
	require 'prepare.php';
	require 'information/debug.php';
	
	$information=new information($imagefile);
	$prepare=new prepare($information->folder);
	
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
		$y='';
		echo 'Svarte bokstaver:<br>';
		foreach($blackletters as $key=>$letter)
		{
			$prevy=$y;
			$y=substr($key,strpos($key,'-')+1);
			$row[$key]=$letter;
			if($y!=$prevy)
			{
				$debug->displayimages($row,'black',$ocrletters);
				echo '<br>';
				unset($row);
			}
		}
		echo '<br>Blanke:<br>';
		echo nl2br(print_r($blankletters,true));
		echo 'OCR:<br>';
		echo nl2br(print_r($ocrletters,true));
	}
	$tiles=$information->tiles;
		foreach ($ocrletters as $key=>$letter)
		{
			if(!$blankletters[$key])
				$tiles[$letter]--;
			else
				$tiles['blank']--;
		}
		return $tiles;
}
?>