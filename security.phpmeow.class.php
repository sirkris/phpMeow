<?php

class phpmeow_security
{
	/* Initializes session security data.  Inherit data from associated IP address, if applicable.  --Kris */
	function initialize()
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
	
	/* Track a failed attempt and react accordingly.  --Kris */
	function track_failure()
	{
		$_SESSION["phpmeow_failed_attempts"]++;
		$_SESSION["phpmeow_last_failed_attempt"] = time();
		
		// TODO - ipban stuff, react
	}
}
