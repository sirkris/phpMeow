<?php

class phpmeow_security
{
	/* Initializes session security data.  Inherit data from associated IP address, if applicable.  --Kris */
	public function __construct()
	{
		if ( !isset( $_SESSION["phpmeow_attempts"] ) )
		{
			$_SESSION["phpmeow_attempts"] = 0;
			$_SESSION["phpmeow_failed_attempts"] = 0;
			$_SESSION["phpmeow_last_attempt"] = 0;
			$_SESSION["phpmeow_last_failed_attempt"] = 0;
			$_SESSION["phpmeow_attempts_log"] = array();  // Indexed by timestamp, contains array of data.  --Kris
		}
		
		// TODO - Load ipban
	}
	
	/* Load ipban configuration file.  --Kris */
	function load_ipban_config()
	{
		
	}
	
	/* Clean-up outdated entries in ipban configuration file.  --Kris */
	function config_housekeeping()
	{
		
	}
	
	/* Log an attempt, regardless of pass or fail.  --Kris */
	function log_attempt( $pass )
	{
		$_SESSION["phpmeow_attempts"]++;
		$_SESSION["phpmeow_last_attempt"] = $phpmeow_cur_time;
		$_SESSION["phpmeow_attempts_log"][$phpmeow_cur_time] = array( "pass" => $pass );
		
		if ( $pass == FALSE )
		{
			self::track_failure();
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
