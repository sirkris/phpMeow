<?php

class phpmeow_animal
{
	function load_image( $imagepath, $widthmod = 0, $heightmod = 0 )
	{
		require( "config.phpmeow.php" );
		
		$im = @imagecreatefromjpeg( $imagepath );
		
		if ( !$im )
		{
			$im = imagecreatetruecolor( $phpmeow_animal_width + $widthmod, $phpmeow_animal_height + $heightmod );
			
			$bgc = imagecolorallocate( $im, 255, 255, 255 );
			$tc = imagecolorallocate( $im, 0, 0, 0 );
			
			imagefilledrectangle( $im, 0, 0, $phpmeow_animal_width + $widthmod, $phpmeow_animal_height + $heightmod, $bgc );
			
			imagestring( $im, 1, 5, 5, "Error loading : " . $imagepath, $tc );
		}
		
		if ( $widthmod != 0 || $heightmod != 0 )
		{
			$im_old = $im;
			
			$im = imagecreatetruecolor( $phpmeow_animal_width + $widthmod, $phpmeow_animal_height + $heightmod );
			
			imagecopyresized( $im, $im_old, 0, 0, 0, 0, $phpmeow_animal_width + $widthmod, $phpmeow_animal_height + $heightmod, imagesx( $im_old ), imagesy( $im_old ) );
		}
		
		return $im;
	}
	
	function add_static( $im )
	{
		require( "config.phpmeow.php" );
		
		$coefficient = ( imagesx( $im ) * imagesy( $im ) ) / 10;
		
		$static = round( ( mt_rand( $phpmeow_min_static, $phpmeow_max_static ) / 100 ) * $coefficient );
		
		for ( $sloop = 1; $sloop <= $static; $sloop++ )
		{
			$static_length = mt_rand( 0, $phpmeow_static_max_length );
			
			$static_r = mt_rand( 0, 255 );
			$static_g = mt_rand( 0, 255 );
			$static_b = mt_rand( 0, 255 );
			
			$static_color = imagecolorallocate( $im, $static_r, $static_g, $static_b );
			
			$static_x1 = mt_rand( 0, imagesx( $im ) );
			$static_y1 = mt_rand( 0, imagesy( $im ) );
			$static_x2 = mt_rand( $static_x1 - $static_length, $static_x1 + $static_length );
			$static_y2 = mt_rand( $static_y1 - $static_length, $static_y1 + $static_length );
			
			imageline( $im, $static_x1, $static_y1, $static_x2, $static_y2, $static_color );
		}
		
		return $im;
	}
	
	function modify_tint( $im )
	{
		require( "config.phpmeow.php" );
		
		$tint_r = 0;
		$tint_g = 0;
		$tint_b = 0;
		if ( mt_rand( 1, $phpmeow_tint_probability ) == 1 )
		{
			$tint_r = mt_rand( 1, $phpmeow_tint_max );
		}
		if ( mt_rand( 1, $phpmeow_tint_probability ) == 1 )
		{
			$tint_g = mt_rand( 1, $phpmeow_tint_max );
		}
		if ( mt_rand( 1, $phpmeow_tint_probability ) == 1 )
		{
			$tint_b = mt_rand( 1, $phpmeow_tint_max );
		}
		
		if ( $tint_r != 0 || $tint_g != 0 || $tint_b != 0 )
		{
			imagefilter( $im, IMG_FILTER_COLORIZE, $tint_r, $tint_g, $tint_b );
		}
		
		return TRUE;
	}
	
	function sketch( $im )
	{
		require( "config.phpmeow.php" );
		
		if ( mt_rand( 1, $phpmeow_sketch_probability ) == 1 )
		{
			imagefilter( $im, IMG_FILTER_MEAN_REMOVAL );
		}
	}
	
	function negative( $im )
	{
		require( "config.phpmeow.php" );
		
		if ( mt_rand( 1, $phpmeow_negative_probability ) == 1 )
		{
			imagefilter( $im, IMG_FILTER_NEGATE );
		}
	}
	
	function blur( $im )
	{
		require( "config.phpmeow.php" );
		
		if ( mt_rand( 1, $phpmeow_blur_probability ) == 1 )
		{
			if ( mt_rand( 1, 2 ) == 1 )
			{
				imagefilter( $im, IMG_FILTER_GAUSSIAN_BLUR );
			}
			else
			{
				imagefilter( $im, IMG_FILTER_SELECTIVE_BLUR );
			}
		}
	}
	
	function grayscale( $im )
	{
		require( "config.phpmeow.php" );
		
		if ( mt_rand( 1, $phpmeow_grayscale_probability ) == 1 )
		{
			imagefilter( $im, IMG_FILTER_GRAYSCALE );
		}
	}
	
	/* This sends the image directly to the browser!  --Kris */
	function render( $im )
	{
		header( "Content-Type: image/jpeg" );
		
		return imagejpeg( $im );
	}
	
	function create( $imagepath )
	{
		
	}
}
