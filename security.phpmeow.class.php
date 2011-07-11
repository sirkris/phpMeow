<?php

class phpmeow_security
{
	/* Initializes session security data.  Inherit data from associated IP address, if applicable.  --Kris */
	public function __construct()
	{
		require( "config.phpmeow.php" );
		
		if ( $phpmeow_security_enable == FALSE )
		{
			return;
		}
		
		if ( !isset( $_SESSION["phpmeow_attempts"] ) )
		{
			$_SESSION["phpmeow_attempts"] = 0;  // Set, but currently unused.  --Kris
			$_SESSION["phpmeow_failed_attempts"] = 0;
			$_SESSION["phpmeow_last_attempt"] = 0;  // Set, but currently unused.  --Kris
			$_SESSION["phpmeow_last_failed_attempt"] = 0;
			$_SESSION["phpmeow_attempts_log"] = array();  // Indexed by timestamp, contains array of data.  Excludes ipban inheritance!  --Kris
			$_SESSION["phpmeow_banned"] = FALSE;  // If TRUE, phpMeow will display a message instead of blocks and fail on any POST attempt.  --Kris
			$_SESSION["phpmeow_ban_expiration"] = 0;
			$_SESSION["phpmeow_probation"] = FALSE;
			$_SESSION["phpmeow_probation_expiration"] = 0;
		}
		
		/* Populate session data with logs for current IP address, if applicable.  --Kris */
		if ( $phpmeow_security_use_ip == TRUE )
		{
			$ipban_ini = self::load_ipban_config();
			self::ipban_dump_to_session( $ipban_ini );
		}
		
		/* Handle any expired bans/probations.  --Kris */
		if ( $_SESSION["phpmeow_ban_expiration"] <= $phpmeow_cur_time 
			&& $_SESSION["phpmeow_ban_expiration"] > 0 )
		{
			$_SESSION["phpmeow_banned"] = FALSE;
			$_SESSION["phpmeow_ban_expiration"] = 0;
		}
		
		if ( $_SESSION["phpmeow_probation_expiration"] <= $phpmeow_cur_time 
			&& $_SESSION["phpmeow_probation_expiration"] > 0 )
		{
			$_SESSION["phpmeow_probation"] = FALSE;
			$_SESSION["phpmeow_probation_expiration"] = 0;
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
	
	/* Add session data into ipban (replace).  --Kris */
	function session_add_to_ipban( $ipban_ini = array(), $save = TRUE )
	{
		if ( empty( $ipban_ini ) )
		{
			$ipban_ini = self::load_ipban_config();
		}
		
		if ( $_SESSION["phpmeow_banned"] == TRUE )
		{
			if ( $_SESSION["phpmeow_ban_expiration"] == 0 )
			{
				$ipban_ini["Permanent"][$_SERVER["REMOTE_ADDR"]] = 0;
			}
			else
			{
				$ipban_ini["Temporary"][$_SERVER["REMOTE_ADDR"]] = $_SESSION["phpmeow_ban_expiration"];
			}
		}
		
		$ipban_ini["Tracking"][$_SERVER["REMOTE_ADDR"]] = $_SESSION["phpmeow_failed_attempts"] . "," . $_SESSION["phpmeow_last_failed_attempt"];
		
		if ( $save == TRUE )
		{
			return self::save_to_ini( $ipban_ini );
		}
		else
		{
			return TRUE;
		}
	}
	
	/* Save INI file.  Preserve section names and comments, populate the rest from array.  --Kris */
	function save_to_ini( $ipban_ini )
	{
		$ini_old = explode( "\r\n", trim( file_get_contents( "ipban.phpmeow.ini" ) ) );
		
		if ( !( $file = fopen( "ipban.phpmeow.ini", "w" ) ) )
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
				if ( strcasecmp( substr( $ini_old_line, 0, 1 ), substr( $skipsalt, $saltloop, 1 ) ) == 0 )
				{
					$skip = TRUE;
					break;
				}
			}
			
			if ( $skip == TRUE )
			{
				continue;
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
	function config_housekeeping( $ipban_ini = array() )
	{
		require( "config.phpmeow.php" );
		
		if ( empty( $ipban_ini ) )
		{
			$ipban_ini = self::load_ipban_config();
		}
		
		$ipban_ini_new = array();
		foreach ( $ipban_ini as $section => $sectiondata )
		{
			if ( strcmp( $section, "Temporary" ) == 0 )
			{
				foreach ( $sectiondata as $bannedip => $banexp )
				{
					if ( $phpmeow_cur_time < $banexp )
					{
						$ipban_ini_new[$section][$bannedip] = $banexp;
					}
				}
			}
			else
			{
				$ipban_ini_new[$section] = $sectiondata;
			}
		}
		
		$ipban_ini_new["Housekeeping"]["Cleaned"] = $phpmeow_cur_time;
		
		return $ipban_ini_new;
	}
	
	/* Log an attempt, regardless of pass or fail.  --Kris */
	function log_attempt( $pass )
	{
		require( "config.phpmeow.php" );
		
		if ( $phpmeow_security_enable == FALSE )
		{
			return;
		}
		
		$_SESSION["phpmeow_attempts"]++;
		$_SESSION["phpmeow_last_attempt"] = $phpmeow_cur_time;
		$_SESSION["phpmeow_attempts_log"][$phpmeow_cur_time] = array( "pass" => $pass );
		
		if ( $pass == FALSE )
		{
			self::track_failure();
		}
		
		if ( $phpmeow_security_use_ip == TRUE )
		{
			self::session_add_to_ipban();
		}
	}
	
	/* Track a failed attempt and react accordingly.  --Kris */
	function track_failure()
	{
		require( "config.phpmeow.php" );
		
		$_SESSION["phpmeow_failed_attempts"]++;
		$_SESSION["phpmeow_last_failed_attempt"] = $phpmeow_cur_time;
		
		/* If a permanent ban is in place, leave so the expiration won't be set to temporary.  --Kris */
		if ( $_SESSION["phpmeow_banned"] == TRUE 
			&& $_SESSION["phpmeow_ban_expiration"] == 0 )
		{
			return;
		}
		
		/* If user is already in probation and/or banned, trigger an automatic 1-hour lockout and reset the probation to 24 hours.  --Kris */
		if ( $_SESSION["phpmeow_probation"] == TRUE 
			|| $_SESSION["phpmeow_banned"] == TRUE )
		{
			$_SESSION["phpmeow_banned"] = TRUE;
			$_SESSION["phpmeow_ban_expiration"] = $phpmeow_cur_time + pow( 60, 2 );
			$_SESSION["phpmeow_probation"] = TRUE;
			$_SESSION["phpmeow_probation_expiration"] = $phpmeow_cur_time + (pow( 60, 2 ) * 24);
		}
		/* Frequent failures in a certain timeframe will trigger an automatic 5-minute ban.  Designed to catch robots, not people.  --Kris */
		else
		{
			$failures = array();  // Failures[(time period in seconds)] = (number of failures in that time period)
			$failures[5] = 0;
			$failures[15] = 0;
			$failures[20] = 0;
			$failures[300] = 0;
			
			/* Number of failures for each period that will trigger a lockout.  Feel free to tweak to your liking.  Must match failures array keys!  --Kris */
			$lockout = array();
			$lockout[5] = 2;
			$lockout[15] = 3;
			$lockout[20] = 4;
			$lockout[300] = 5;
			
			/* Collect our stats.  --Kris */
			foreach ( $_SESSION["phpmeow_attempts_log"] as $timestamp => $logdata )
			{
				if ( $logdata["pass"] == FALSE )
				{
					foreach ( $failures as $failkey => $ignore )
					{
						if ( $timestamp >= $phpmeow_cur_time - $failkey )
						{
							$failures[$failkey]++;
						}
					}
				}
			}
			
			/* Compare our stats with the lockout rules.  If any match, trigger a 5-minute lockout (ban).  --Kris */
			foreach ( $lockout as $seconds => $limit )
			{
				if ( $failures[$seconds] >= $limit )
				{
					$_SESSION["phpmeow_banned"] = TRUE;
					$_SESSION["phpmeow_ban_expiration"] = $phpmeow_cur_time + 300;
					
					break;
				}
			}
		}
		
		/* Sleep a bit before sending a response, if enabled.  --Kris */
		if ( $phpmeow_security_failure_wait > 0 )
		{
			sleep( $phpmeow_security_failure_wait );
		}
	}
}
