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
		
		/* Begin HTML generation.  --Kris */
		print "\r\n<!-- Begin phpMeow code. -->\r\n";
		
		$top_margin = 5;
		
		$totalwidth = ($phpmeow_blocks_x * ($phpmeow_animal_width * 2)) + ($phpmeow_blocks_x * $phpmeow_padding_x);
		$totalheight = $top_margin + ($phpmeow_blocks_y * ($phpmeow_animal_height * 2)) + ($phpmeow_blocks_y * $phpmeow_padding_y);
		
		print "<div style=\"position: absolute; left: 0px; top: " . $top_margin . "px; width: " . $totalwidth . "px; text-align: center\">\r\n";
		
		print "<div style=\"width: 92%; margin: auto; background-color: #DEDEFF; border: 1px solid black\">\r\n";
		
		print "<b style=\"color: blue\">KittenAuth</b><br /><b style=\"color: navy\">To prove you're not a robot, please click <i>all</i> blocks that contain </b>";
		
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
		
		print "<b style=\"color: red\">" . $requirements . "</b>.\r\n";
		
		print "</div>\r\n";
		
		print "</div>\r\n";
		
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
		
		print "<div style=\"position: absolute; left: 0px; top: " . ($totalheight + 50) . "px; width: " . $totalwidth . "px; text-align: center\">\r\n";
		print "<span class=\"credit\">";
		print "Powered by phpMeow v" . $phpmeow_version . ".&nbsp; ";
		print "Created by <a href=\"http://www.facebook.com/Kris.Craig\" target=\"_blank\">Kris Craig</a>.";
		print "</span>\r\n";
		print "</div>\r\n";
		
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
}
