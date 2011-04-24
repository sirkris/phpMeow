<?php

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
