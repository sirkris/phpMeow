<?php

class phpmeow_block
{
	/* Determine which animals to allocate to a block, depending on whether it's been designated as a correct answer or not.  --Kris */
	function allocate( $whichblock, $correct_blocks, $required )
	{
		require( "config.phpmeow.php" );
		
		$allocation = array();
		
		$correct = FALSE;
		foreach ( $correct_boxes as $ckey => $cval )
		{
			if ( $whichblock == $cval )
			{
				$correct = TRUE;
				break;
			}
		}
		
		$total = 4;
		if ( $correct == TRUE )
		{
			foreach ( $required as $rkey => $requirement )
			{
				foreach ( $requirement as $creature => $quantity )
				{
					for ( $qloop = 1; $qloop <= $quantity; $qloop++ )
					{
						$allocation[$total] = $creature;
						$total--;
					}
				}
			}
		}
		
		/* Random animals/junk to fill any remaining squares.  --Kris */
		do
		{
			for ( $picloop = 1; $picloop <= $total; $picloop++ )
			{
				do
				{
					$allocation[$picloop] = mt_rand( 0, count( $arr ) - 1 );
					
					$dup = FALSE;
					for ( $subloop = 1; $subloop < $picloop; $subloop++ )
					{
						if ( $allocation[$picloop] == $allocation[$subloop] )
						{
							$dup = TRUE;
							break;
						}
					}
				} while ( $dup == TRUE );
				
				$allocation[$picloop] = $arr[$allocation[$picloop]];
			}
			
			/* If not intended to be correct, make sure it isn't correct anyway by random chance.  If it is, regenerate.  --Kris */
			$invalid = FALSE;
			if ( $correct == FALSE )
			{
				$invalid = TRUE;
				foreach ( $required as $rkey => $requirement )
				{
					foreach ( $requirement as $creature => $quantity )
					{
						if ( count( array_keys( $allocation, $creature ) ) != $quantity )
						{
							$invalid = FALSE;
							break;
						}
					}
				}
			}
			/* If it is intended to be correct, make sure the randomness didn't screw up the quantity.  --Kris */
			else
			{
				foreach ( $required as $rkey => $requirement )
				{
					foreach ( $requirement as $creature => $quantity )
					{
						if ( count( array_keys( $allocation, $creature ) ) != $quantity )
						{
							$invalid = TRUE;
							break;
						}
					}
				}
			}
		} while ( $invalid == TRUE );
		
		shuffle( $allocation );
		
		return $allocation;
	}
	
	/*
	 * Array keys must correspond to the following layout:
	 * 
	 * 01
	 * 23
	 * 
	 * --Kris
	 */
	function merge_animals( $ims = array() )
	{
		require_once( "animal.phpmeow.class.php" );
		
		if ( count( $ims ) != 4 )
		{
			return FALSE;
		}
		
		$img_width = imagesx( $ims[0] ) + imagesx( $ims[1] );
		$img_height = imagesy( $ims[0] ) + imagesy( $ims[2] );
		
		if ( $img_width == 0 || $img_height == 0 )
		{
			return FALSE;
		}
		
		$blockim = imagecreatetruecolor( $img_width, $img_height );
		
		/* This approach allows us to completely hide the animal image paths from the client.  --Kris */
		imagecopy( $blockim, $ims[0], 0, 0, 0, 0, imagesx( $ims[0] ), imagesy( $ims[0] ) );
		imagecopy( $blockim, $ims[1], imagesx( $ims[0] ), 0, 0, 0, imagesx( $ims[1] ), imagesy( $ims[1] ) );
		imagecopy( $blockim, $ims[2], 0, imagesy( $ims[0] ), 0, 0, imagesx( $ims[2] ), imagesy( $ims[2] ) );
		imagecopy( $blockim, $ims[3], imagesx( $ims[0] ), imagesy( $ims[0] ), 0, 0, imagesx( $ims[3] ), imagesy( $ims[3] ) );
		
		return $blockim;
	}
	
	/* Saves to disk for swapping purposes.  --Kris */
	function save( $blockim, $filename )
	{
		return imagejpeg( $blockim, $filename );
	}
	
	/* Clean-up the temporary image file.  --Kris */
	function destroy( $filekey )
	{
		$filename = $_SESSION[$filekey];
		
		$ok = unlink( $filename );
		
		unset( $_SESSION[$filekey] );
		
		return $ok;
	}
}
