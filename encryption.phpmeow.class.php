<?php

class phpmeow_encryption
{
	function encrypt_string( $text, $use_sessionid = FALSE )
	{
		require( "config.phpmeow.php" );
		
		/* First, make sure the specified protocol is supported by the PHP install.  --Kris */
		switch ( $phpmeow_enchash )
		{
			default:
				return FALSE;
				break;
			case "md5":
				if ( !function_exists( "md5" ) )
				{
					return FALSE;
				}
				break;
			case "sha1":
				if ( !function_exists( "sha1" ) )
				{
					return FALSE;
				}
				break;
			case "sha256":
			case "sha512":
				if ( !function_exists( "hash" ) 
					|| in_array( $phpmeow_enchash, hash_algos() ) == FALSE )
				{
					return FALSE;
				}
				break;
		}
		
		if ( $use_sessionid == TRUE )
		{
			//session_start();
			$text = session_id() . $text . self::encrypt_string( session_id(), FALSE );
		}
		
		/* Return the encrypted string and we're done!  --Kris */
		switch ( $phpmeow_enchash )
		{
			default:
				die( "ERROR!  Sanity check passed for encrypt_string but functionality for $phpmeow_enchash protocol not added!" );
				break;
			case "md5":
				return md5( $text );
				break;
			case "sha1":
				return sha1( $text );
				break;
			case "sha256":
			case "sha512":
				return hash( $phpmeow_enchash, $text );
				break;
		}
	}	
}
