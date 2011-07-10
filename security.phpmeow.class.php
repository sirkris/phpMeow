<?php

class phpmeow_security
{
	/* Initializes session security data.  Inherit data from associated IP address, if applicable.  --Kris */
	function initialize()
	{
		if ( !isset( $_SESSION["phpmeow_attempts"] ) )
		{
			
		}
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
		
	}
}
