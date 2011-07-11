<?php

class phpmeow_security
{
	/* Initializes session security data.  Inherit data from associated IP address, if applicable.  --Kris */
	public function __construct()
	{
		require( "config.phpmeow.php" );
		
		if ( !isset( $_SESSION["phpmeow_attempts"] ) )
		{
			$_SESSION["phpmeow_attempts"] = 0;  // Set, but currently unused.  --Kris
			$_SESSION["phpmeow_failed_attempts"] = 0;
			$_SESSION["phpmeow_last_attempt"] = 0;  // Set, but currently unused.  --Kris
			$_SESSION["phpmeow_last_failed_attempt"] = 0;
			$_SESSION["phpmeow_attempts_log"] = array();  // Indexed by timestamp, contains array of data.  Excludes ipban inheritance!  --Kris
			$_SESSION["phpmeow_banned"] = FALSE;  // If TRUE, phpMeow will display a message instead of blocks and fail on any POST attempt.  --Kris
			$_SESSION["phpmeow_ban_expiration"] = 0;
		}
		
		/* Populate session data with logs for current IP address, if applicable.  --Kris */
		if ( $phpmeow_security_use_ip == TRUE )
		{
			$ipban_ini = self::load_ipban_config();
			self::ipban_dump_to_session( $ipban_ini );
		}
	}
	
	/* Load ipban configuration file.  --Kris */
	function load_ipban_config()
	{
		require( "config.phpmeow.php" );
		
		$ipban_ini = array();
		$ipban_ini = parse_ini_file( "ipban.phpmeow.ini", TRUE );
		
		if ( $phpmeow_cur_time > $ipban_ini["Housekeeping"]["Cleaned"] + $phpmeow_security_ipban_cleanup_wait )
		{
			$ipban_ini = self::config_housekeeping( $ipban_ini );
		}
		
		return $ipban_ini;
	}
	
	/* Load ipban data into session (replace).  --Kris */
	function ipban_dump_to_session( $ipban_ini )
	{
		require( "config.phpmeow.php" );
		
		/* Check for temporary bans.  Ignore expired ones that haven't been cleaned-up yet.  --Kris */
		if ( isset( $ipban_ini["Temporary"][$_SERVER["REMOTE_ADDR"]] ) 
			&& $ipban_ini["Temporary"][$_SERVER["REMOTE_ADDR"]] > $phpmeow_cur_time )
		{
			$_SESSION["phpmeow_banned"] = TRUE;
			$_SESSION["phpmeow_ban_expiration"] = $ipban_ini["Temporary"][$_SERVER["REMOTE_ADDR"]];
		}
		
		/* Check for permanent bans.  The value is ignored.  --Kris */
		if ( isset( $ipban_ini["Permanent"][$_SERVER["REMOTE_ADDR"]] ) )
		{
			$_SESSION["phpmeow_banned"] = TRUE;
			$_SESSION["phpmeow_ban_expiration"] = 0;
		}
		
		/* Updated failed attempts.  This is NOT added to the session attempts log array!  --Kris */
		if ( isset( $ipban_ini["Tracking"][$_SERVER["REMOTE_ADDR"]] ) )
		{
			$faildata = explode( ",", $ipban_ini["Tracking"][$_SERVER["REMOTE_ADDR"]] );
			
			$_SESSION["phpmeow_failed_attempts"] = $faildata[0];
			$_SESSION["phpmeow_last_failed_attempt"] = $faildata[1];
		}
	}
	
	/* Add session data into ipban (update).  --Kris */
	function session_add_to_ipban( $pass, $ipban_ini = array() )
	{
		if ( empty( $ipban_ini ) )
		{
			$ipban_ini = self::load_ipban_config();
		}
		
		// TODO - The crap that goes here.
	}
	
	/* Save INI file.  Preserve section names and comments, populate the rest from array.  --Kris */
	function save_to_ini( $ipban_ini )
	{
		$ini_old = explode( "\r\n", file_get_contents( "ipban.phpmeow.ini" ) );
		
		if ( !( $file = fopen( "ipban.phpmeow.ini", w ) ) )
		{
			return FALSE;
		}
		
		/* If first character is any of these, skip.  --Kris */
		$skipsalt = "abcdefghijklmnopqrstuvwxyz1234567890";
		
		foreach ( $ini_old as $ini_old_line )
		{
			$skip = FALSE;
			for ( $saltloop = 0; $saltloop < strlen( $skipsalt ); $saltloop++ )
			{
				if ( strcasecmp( $ini_old_line{0}, $skipsalt{$saltloop} ) == 0 )
				{
					$skip = TRUE;
					break;
				}
			}
			
			fputs( $file, $ini_old_line . "\r\n" );
			
			/* If it's a section header, output the data for that section.  --Kris */
			if ( strcmp( $ini_old_line{0}, "[" ) == 0 
				&& strcmp( $ini_old_line{strlen( $ini_old_line ) - 1}, "]" ) == 0 )
			{
				$section = substr( $ini_old_line, 1, strlen( $ini_old_line ) - 2 );
				
				foreach ( $ipban_ini[$section] as $var => $val )
				{
					fputs( $file, $var . " = " . $val . "\r\n" );
				}
			}
		}
		
		fclose( $file );
		
		return TRUE;
	}
	
	/* Clean-up outdated entries in ipban configuration file.  --Kris */
	function config_housekeeping()
	{
		
	}
	
	/* Log an attempt, regardless of pass or fail.  --Kris */
	function log_attempt( $pass )
	{
		require( "config.phpmeow.php" );
		
		$_SESSION["phpmeow_attempts"]++;
		$_SESSION["phpmeow_last_attempt"] = $phpmeow_cur_time;
		$_SESSION["phpmeow_attempts_log"][$phpmeow_cur_time] = array( "pass" => $pass );
		
		if ( $pass == FALSE )
		{
			self::track_failure();
		}
		
		if ( $phpmeow_security_use_ip == TRUE )
		{
			self::session_add_to_ipban( $pass );
		}
	}
	
	/* Track a failed attempt and react accordingly.  --Kris */
	function track_failure()
	{
		$_SESSION["phpmeow_failed_attempts"]++;
		$_SESSION["phpmeow_last_failed_attempt"] = $phpmeow_cur_time;
		
		// TODO - ipban stuff, react
	}
}
