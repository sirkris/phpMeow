<?php

/*
 * phpMeow - A Cute and Fuzzy Alternative to CAPTCHA
 * Created by Kris Craig.  April - July, 2011.
 * 
 * phpMeow is the first fully-functional, secure 
 * implementation of KittenAuth in PHP.
 * 
 * This software is open-source and you're free to 
 * use and/or distribute it as you see fit.  See 
 * LICENSE file for more information.
 * 
 * Get the latest version at:  http://www.github.com/sirkris/phpmeow
 */

foreach ( $_GET as $getvar => $getval )
{
	$$getvar = $getval;
}

require( "config.phpmeow.php" );

$session = new phpmeow_session();
$session->start();

if ( $_SESSION[$getkey] != NULL )
{
	$im = @imagecreatefromjpeg( $_SESSION[$getkey] );
	
	phpmeow_animal::render( $im );
	
	phpmeow_block::destroy( $getkey );
}
else
{
	$imagedir = new phpmeow_imagedir();
	
	$animals = $imagedir->load_cute_fuzzy_animals( $phpmeow_animalsdir );
	
	/* If you're not calling this script client-side, it should be safe not to encrypt.  --Kris */
	if ( $_GET["ue"] == 1 )
	{
		$image = $imgpath;
	}
	else
	{
		/* Less efficient, but hella more secure.  --Kris */
		$encryption = new phpmeow_encryption();
		
		$image = NULL;
		foreach ( $animals as $animal => $files )
		{
			foreach ( $files as $fkey => $file )
			{
				if ( strcmp( $encryption->encrypt_string( $file, TRUE ), $imgpath ) == 0 )
				{
					$image = $file;
					break;
				}
			}
			
			if ( $image != NULL )
			{
				break;
			}
		}
	}
	
	phpmeow_animal::create( $image, $xmod, $ymod );
}
