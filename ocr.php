<?Php
include 'dependcheck.php';
depend('tesseract');
function ocr($im,$outfile)
{

	//shell_exec("convert \"$image\" \"/tmp/$basename.tif\"");
	imagepng($im,$image='/tmp/image.png');
	//shell_exec($cmd="tesseract \"/tmp/$basename.tif\" \"$outfile\"");
	shell_exec("tesseract \"$image\" \"$outfile\" -psm 10 -l nor");
	//unlink("/tmp/$basename.tif");
	$text=trim(file_get_contents($outfile.'.txt'));
	$text=str_replace(array('0'),array('O'),$text,$replacements);
	//$replaced=$text;
	/*if($replaced!=$text) //Sjekk om det er gjort erstatninger og lag ny fil
	{
		unlink($outfile);
		$text=$replaced;
		file_put_contents($outfile,$text);
	}*/
	/*if(strpos('â€',$text)!==false)
		$text=*/

	return $text;
}
