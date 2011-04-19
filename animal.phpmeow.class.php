<?php

class phpmeow_animal
{
	function load_image( $imagepath )
	{
		require( "config.phpmeow.php" );
		
		$im = @imagecreatefromjpeg( $imagepath );
		
		if ( !$im )
		{
			$im = imagecreatetruecolor( $phpmeow_animal_width, $phpmeow_animal_height );
			
			$bgc = imagecolorallocate( $im, 255, 255, 255 );
			$tc = imagecolorallocate( $im, 0, 0, 0 );
			
			imagefilledrectangle( $im, 0, 0, $phpmeow_animal_width, $phpmeow_animal_height, $bgc );
			
			imagestring( $im, 1, 5, 5, "Error loading : " . $imagepath, $tc );
		}
		
		return $im;
	}
}
