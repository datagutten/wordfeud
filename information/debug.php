<?Php
class debug
{
	public function displayimages($images,$runkey=1)
	{
			foreach ($images as $key=>$image)
			{
				imagepng($image,"testbilder/tile_$runkey.$key.png",9);
				echo "<img src=\"testbilder/tile_$runkey.$key.png\" />&nbsp;\n";
			}
	}
	public function displaycolorlimits($colorlimits)
	{
		foreach($colorlimits as $key=>$limit)
		{
			echo "$key min: ".dechex($limit[0])."\n";
			echo "$key max: ".dechex($limit[1])."\n";	
		}
	}	
}