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

class phpmeow_session
{
	/* Avoids that pesky notice error if the session was already started previously in the stack.  --Kris */
	function start()
	{
		if ( !isset( $_SESSION ) )
		{
			session_start();
		}
	}
	
	/* Generates a random string for use as a new sessionid or any other purpose.  --Kris */
	function generate_sid( $chars = 100, $alpha = TRUE, $numeric = TRUE, $symbols = TRUE, $timestamp = TRUE )
	{
		if ( $chars <= 0 || !is_numeric( $chars ) )
		{
			return FALSE;
		}
		
		$salt = NULL;
		
		if ( $alpha == TRUE )
		{
			$salt .= "abcdefghijklmnopqrstuvwxyz";
		}
		
		if ( $numeric == TRUE )
		{
			$salt .= "1234567890";
		}
		
		if ( $symbols == TRUE )
		{
			$salt .= "-_";
		}
		
		$sid = NULL;
		for ( $c = 1; $c <= $chars; $c++ )
		{
			$sid .= $salt{mt_rand( 0, strlen( $salt ) - 1 )};
			
			if ( mt_rand( 0, 1 ) == 1 )
			{
				$sid{strlen( $sid ) - 1} = strtoupper( $sid{strlen( $sid ) - 1} );
			}
		}
		
		if ( $timestamp == TRUE )
		{
			$sid .= time();
		}
		
		return $sid;
	}
}
