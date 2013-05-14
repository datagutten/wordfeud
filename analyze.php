<?Php
function analyze($letters,$blank,$tiles)
{
	foreach ($letters as $key=>$letter)
	{
		if(!$blank[$key])
			$tiles[$letter]--;
		else
			$tiles['blank']--;
	}
	return $tiles;
}