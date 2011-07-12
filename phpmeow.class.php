<?php

class phpmeow
{
	/* Each arg must be an array in $varname => $value format.  --Kris */
	public function __construct()
	{
		$args = func_get_args();
		
		foreach ( $args as $arr )
		{
			if ( is_array( $arr ) && !empty( $arr ) )
			{
				foreach ( $arr as $varname => $value )
				{
					$this->$varname = $value;
				}
			}
		}
	}
	
	/* Call this function from an iframe embedded in your script (ideally just above your form's "submit" button) to invoke phpMeow!  --Kris */
	function main()
	{
		require( "config.phpmeow.php" );
		
		/* You can override any config.phpmeow.php variables when initializing the class instance (at your own risk!).  --Kris */
		// TODO - Make these overrides apply in other classes as well; in the meantime, using this feature is not recommended!
		foreach ( $this as $varname => $value )
		{
			if ( !isset( $$varname ) || $phpmeow_allowoverride == TRUE )
			{
				$$varname = $value;
			}
		}
		
		/* Initialize!  --Kris */
		$session = new phpmeow_session();
		$session->start();
		
		$security = new phpmeow_security();
		
		/* If user is locked-out (banned as possible robot/BFG), don't waste resources on it.  --Kris */
		if ( $_SESSION["phpmeow_banned"] == FALSE )
		{
			$block = new phpmeow_block();
			$imagedir = new phpmeow_imagedir();
			$animal = new phpmeow_animal();
			
			$animals = $imagedir->load_cute_fuzzy_animals( $phpmeow_animalsdir );
			
			$animarr = array();
			foreach ( $animals as $name => $animarr2 )
			{
				$animarr[] = $name;
			}
			
			/* Setup our challenge parameters.  --Kris */
			$required = self::get_requirements( $animals, $animarr );
			$correct_blocks = self::get_correct_blocks();
			
			/* Store our session data for validation.  --Kris */
			$_SESSION["phpmeow_correct_blocks"] = $correct_blocks;
		}
		
		/* Begin HTML generation.  --Kris */
		print "\r\n<!-- Begin phpMeow code. -->\r\n";
		
		$top_margin = 5;
		
		$totalwidth = ($phpmeow_blocks_x * ($phpmeow_animal_width * 2)) + ($phpmeow_blocks_x * $phpmeow_padding_x);
		$totalheight = $top_margin + ($phpmeow_blocks_y * ($phpmeow_animal_height * 2)) + ($phpmeow_blocks_y * $phpmeow_padding_y);
		
		print "<div style=\"position: absolute; left: 0px; top: " . $top_margin . "px; width: " . $totalwidth . "px; text-align: center\">\r\n";
		
		print "<div style=\"width: 92%; margin: auto; background-color: #DEDEFF; border: 1px solid black\">\r\n";
		
		print "<b style=\"color: blue\">KittenAuth</b><br />";
		
		/* If a legitimate human ever sees this lockout message, the rules probably need to be tweaked.  --Kris */
		if ( $_SESSION["phpmeow_banned"] == TRUE )
		{
			print "<br /><b style=\"color: red\">";
			print "We're sorry, but your repeated failed attempts have triggered an automatic lockout.  Please try again in a few minutes.</b>";
		}
		else
		{
			print "<b style=\"color: navy; font-size: 10pt\">To prove you're not a robot, please click <i>all</i> blocks that contain </b>";
			
			$checklist = array();
			foreach ( $required as $rkey => $reqs )
			{
				foreach ( $reqs as $category => $quantity )
				{
					$checklist[] = "$quantity " . ($quantity == 1 ? $phpmeow_singular[$category] : $category);
				}
			}
			
			$requirements = NULL;
			$i = 0;
			foreach ( $checklist as $req )
			{
				$i++;
				if ( $requirements != NULL )
				{
					/* I like the Oxford Comma.  --Kris */
					if ( $i < count( $checklist ) || count( $checklist ) > 2 )
					{
						$requirements .= ",";
					}
					
					$requirements .= " ";
					if ( $i == count( $checklist ) )
					{
						$requirements .= "and ";
					}
				}
				
				$requirements .= $req;
			}
			
			print "<b style=\"color: red; font-size: 10pt\">" . $requirements . "</b>.\r\n";
		}
		
		print "</div>\r\n";
		
		print "</div>\r\n";
		
		if ( $_SESSION["phpmeow_banned"] == FALSE )
		{
			$x = 0;
			$y = 60;
			for ( $divyloop = 1; $divyloop <= $phpmeow_blocks_y; $divyloop++ )
			{
				for ( $divxloop = 1; $divxloop <= $phpmeow_blocks_x; $divxloop++ )
				{
					/* Construct the cage for our furry little friends.  --Kris */
					$whichblock = (($divyloop - 1) * $phpmeow_blocks_x) + $divxloop;
					$allocation = $block->allocate( $whichblock, $correct_blocks, $required, $animarr );
					$images = $block->assign_animals( $allocation, $animals );
					$ims = $block->boxify_images( $images, $animal );
					
					/* Render the block (including the div tag).  --Kris */
					$block->render( $ims, $whichblock, $x, $y );
					
					$x += ($phpmeow_animal_width * 2) + $phpmeow_padding_x;
				}
				
				$x = 0;
				$y += ($phpmeow_animal_height * 2) + $phpmeow_padding_y;
			}
		}
		
		print "<div style=\"position: absolute; left: 0px; top: " . ($totalheight + 50) . "px; width: " . $totalwidth . "px; text-align: center\">\r\n";
		print "<span class=\"credit\">";
		print "Powered by phpMeow v" . $phpmeow_version . ".&nbsp; ";
		print "Created by <a href=\"http://www.facebook.com/Kris.Craig\" target=\"_blank\">Kris Craig</a>.";
		print "</span>\r\n";
		
		if ( $_SESSION["phpmeow_banned"] == FALSE )
		{
			/* Referer is not always reliable.  --Kris */
			print "<input type=\"hidden\" name=\"phpmeow_referer\" id=\"phpmeow_referer\" value=\"" . $_SERVER["SCRIPT_NAME"] . "\" />\r\n";
			
			/* Use this if you want phpMeow to place your submit button at the bottom without having to guess the absolute positioning.  --Kris */
			if ( $phpmeow_submit_include == TRUE )
			{
				print "<br /><br />\r\n";
				print "<input type=\"submit\" name=\"phpmeow_submit\" id=\"phpmeow_submit\" value=\"$phpmeow_submit_value\" />\r\n";
			}
		}
		
		print "</div>\r\n";
		
		if ( isset( $_GET["phpmeow_fail_msg"] ) )
		{
			print "<script language=\"JavaScript\">";
			print "alert( \"" . $_GET["phpmeow_fail_msg"] . "\" );";
			print "</script>";
		}
		
		print "<!-- End phpMeow code. -->\r\n\r\n";
	}
	
	/* Determine what the correct answer will be.  --Kris */
	function get_requirements( $animals, $animarr )
	{
		require( "config.phpmeow.php" );
		
		/* You can override any config.phpmeow.php variables when initializing the class instance (at your own risk!).  --Kris */
		// TODO - Make these overrides apply in other classes as well; in the meantime, using this feature is not recommended!
		foreach ( $this as $varname => $value )
		{
			if ( !isset( $$varname ) || $phpmeow_allowoverride == TRUE )
			{
				$$varname = $value;
			}
		}
		
		$required = array();
		
		$junkmax = mt_rand( 0, 3 );
		$total = 4;
		$max_reqs = 3;
		do
		{
			$required[$total] = array();
			
			$req = mt_rand( 0, count( $animarr ) - 1 );
			$reqnum = mt_rand( 1, ( $total - $junkmax ) );
			
			$dup = FALSE;
			foreach ( $required as $rkey => $rarr )
			{
				if ( isset( $rarr[$animarr[$req]] ) || strcasecmp( $animarr[$req], "junk" ) == 0 )
				{
					$dup = TRUE;
					break;
				}
			}
			
			if ( $dup == FALSE )
			{
				$required[$total][$animarr[$req]] = $reqnum;
				
				$total -= $reqnum;
				
				/* We should limit the number of parameters to 3 so everything will fit.  --Kris */
				if ( count( $required ) >= $max_reqs )
				{
					break;
				}
			}
		} while ( $total > $junkmax );
		
		return $required;
	}
	
	/* Determine which blocks will have the correct answer.  --Kris */
	function get_correct_blocks()
	{
		require( "config.phpmeow.php" );
		
		/* You can override any config.phpmeow.php variables when initializing the class instance (at your own risk!).  --Kris */
		// TODO - Make these overrides apply in other classes as well; in the meantime, using this feature is not recommended!
		foreach ( $this as $varname => $value )
		{
			if ( !isset( $$varname ) || $phpmeow_allowoverride == TRUE )
			{
				$$varname = $value;
			}
		}
		
		$howmany = mt_rand( 2, ($phpmeow_blocks_x * $phpmeow_blocks_y) - 3 );
		
		if ( !( $howmany > 0 ) )
		{
			$howmany = 1;
		}
		
		$correct_blocks = array();
		for ( $total = $howmany; $total > 0; $total-- )
		{
			$pick = mt_rand( 1, $phpmeow_blocks_x * $phpmeow_blocks_y );
			
			$dup = FALSE;
			foreach ( $correct_blocks as $ckey => $cval )
			{
				if ( $pick == $cval )
				{
					$dup = TRUE;
					break;
				}
			}
			
			if ( $dup == TRUE )
			{
				$total++;
			}
			else
			{
				$correct_blocks[] = $pick;
			}
		}
		
		return $correct_blocks;
	}
	
	/* Compares the POST data against the SESSION data and returns whether the user-selected boxes are correct.  --Kris */
	function validate()
	{
		require( "config.phpmeow.php" );
		
		$security = new phpmeow_security();
		
		/* If ban is already in place, reset ban expiration to 1 hour with 24-hour probation.  Do not waste resources logging the attempt.  --Kris */
		if ( $_SESSION["phpmeow_banned"] == TRUE )
		{
			/* Don't bother with permanent bans.  --Kris */
			if ( $_SESSION["phpmeow_ban_expiration"] > 0 )
			{
				$_SESSION["phpmeow_ban_expiration"] = $phpmeow_cur_time + pow( 60, 2 );
				$_SESSION["phpmeow_probation"] = TRUE;
				$_SESSION["phpmeow_probation_expiration"] = $phpmeow_cur_time + (pow( 60, 2 ) * 24);
			}
			
			return FALSE;
		}
		
		if ( !isset( $_POST ) || !isset( $_SESSION ) || !isset( $_SESSION["phpmeow_correct_blocks"] ) || !is_array( $_SESSION["phpmeow_correct_blocks"] ) 
			|| empty( $_SESSION["phpmeow_correct_blocks"] ) )
		{
			$security->log_attempt( FALSE );
			return FALSE;
		}
		
		$selcount = 0;
		foreach ( $_POST as $postvar => $postval )
		{
			if ( strcmp( substr( $postvar, 0, 14 ), "fphpmeow_block" ) )
			{
				continue;
			}
			
			$selcount++;
			$whichblock = substr( $postvar, 14, strlen( $postvar ) - 14 );
			
			$correct = FALSE;
			foreach ( $_SESSION["phpmeow_correct_blocks"] as $correct_block )
			{
				if ( $whichblock == $correct_block )
				{
					$correct = TRUE;
					break;
				}
			}
			
			if ( ( $correct == TRUE && $postval != 1 ) || ( $correct == FALSE && $postval == 1 ) )
			{
				$security->log_attempt( FALSE );
				return FALSE;
			}
		}
		
		if ( $selcount < 1 )
		{
			$security->log_attempt( FALSE );
			return FALSE;
		}
		
		$security->log_attempt( TRUE );
		return TRUE;
	}
	
	/* Redirect to the form and tell the user the answers did not match.  Pass back the post values for optional auto-fill.  --Kris */
	function fail_redirect()
	{
		$getvars = NULL;
		foreach ( $_POST as $postvar => $postval )
		{
			if ( strcmp( substr( $postvar, 0, 8 ), "fphpmeow" ) == 0 
				|| strcmp( substr( $postvar, 0, 7 ), "phpmeow" ) == 0 )
			{
				continue;
			}
			
			if ( $getvars == NULL )
			{
				$getvars .= "?";
			}
			else
			{
				$getvars .= "&";
			}
			
			$getvars .= urlencode( $postvar ) . "=" . urlencode( $postval );
		}
		
		$selcount = 0;
		foreach ( $_POST as $postvar => $postval )
		{
			if ( strcmp( substr( $postvar, 0, 14 ), "fphpmeow_block" ) == 0 && $postval == 1 )
			{
				$selcount++;
			}
		}
		
		if ( $selcount == 0 )
		{
			if ( $getvars == NULL )
			{
				$getvars .= "?";
			}
			else
			{
				$getvars .= "&";
			}
			
			$getvars .= "phpmeow_fail_msg=";
			$getvars .= urlencode( "You forgot to do the KittenAuth verification!  Please try again." );
		}
		else
		{
			$getvars .= "&phpmeow_fail_msg=";
			$getvars .= urlencode( "Your KittenAuth selections were incorrect!  Please try again." );
		}
		
		header( "Location: " . $_POST["phpmeow_referer"] . $getvars );
		die();
	}
	
	/* This will mindlessly auto-fill your form fields from the GET variables IF phpMeow returned a fail.  Use this if you're lazy.  --Kris */
	function autofill()
	{
		if ( !isset( $_GET["phpmeow_fail_msg"] ) )
		{
			return;
		}
		
		print "<script language=\"JavaScript\">\r\n";
		
		foreach ( $_GET as $getvar => $getval )
		{
			if ( strcmp( substr( $getvar, 0, 8 ), "fphpmeow" ) == 0 
				|| strcmp( substr( $getvar, 0, 7 ), "phpmeow" ) == 0 )
			{
				continue;
			}
			
			print "document.getElementById( \"" . $getvar . "\" ).value=\"" . $getval . "\";\r\n";
		}
		
		print "</script>\r\n";
	}
}
