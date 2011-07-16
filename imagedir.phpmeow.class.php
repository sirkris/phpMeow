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

class phpmeow_imagedir
{
	function load_images_from_subdir( $dirpath )
	{
		if ( !is_dir( $dirpath ) || !is_readable( $dirpath ) )
		{
			return FALSE;
		}
		
		if ( $dh = opendir( $dirpath ) )
		{
			$images = array();
			while ( ( $file = readdir( $dh ) ) !== FALSE )
			{
				/* Let's just keep it simple and stick to jpegs.  If you change this, also change the GD2 load function to match.  --Kris */
				if ( strcasecmp( filetype( $dirpath . "/" . $file ), "file" ) == 0 
					&& strcasecmp( substr( $file, strlen( $file ) - 4, 4 ), ".jpg" ) == 0 )
				{
					$images[] = $dirpath . "/" . $file;
				}
			}
			
			closedir( $dh );
			
			return $images;
		}
		else
		{
			return FALSE;
		}
	}
	
	function load_directories( $dirpath )
	{
		if ( !is_dir( $dirpath ) || !is_readable( $dirpath ) )
		{
			return FALSE;
		}
		
		if ( $dh = opendir( $dirpath ) )
		{
			$dirs = array();
			while ( ( $file = readdir( $dh ) ) !== FALSE )
			{
				if ( strcasecmp( filetype( $dirpath . "/" . $file ), "dir" ) == 0 
					&& strcmp( $file, "." ) && strcmp( $file, ".." ) )
				{
					$dirs[] = $file;
				}
			}
			
			closedir( $dh );
			
			return $dirs;
		}
	}
	
	function load_cute_fuzzy_animals( $basepath )
	{
		$animals = array();
		
		if ( !( $dirs = self::load_directories( $basepath ) ) )
		{
			return FALSE;
		}
		
		foreach ( $dirs as $dkey => $dirname )
		{
			$animals[$dirname] = self::load_images_from_subdir( $basepath . "/" . $dirname );
		}
		
		return $animals;
	}
}
