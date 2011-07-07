<?php

class phpmeow
{
	/* If passing args, arg 0 must be an array containing the variable names to which to assign each subsequent arg value.  --Kris */
	public function __construct()
	{
		$args = func_get_args();
		
		if ( is_array( $args[0] ) && count( $args ) - 1 == count( $args[0] ) && count( $args ) > 1 )
		{
			foreach ( $args[0] as $arkey => $varname )
			{
				$this->$varname = $args[$arkey + 1];
			}
		}
	}
	
	function get_requirements()
	{
		require( "config.phpmeow.php" );
		
		$imagedir = new phpmeow_imagedir();
		$animals = $imagedir->load_cute_fuzzy_animals( $phpmeow_animalsdir );
		
		$arr = array();
		foreach ( $animals as $name => $arr2 )
		{
			$arr[] = $name;
		}
		
		$required = array();
		
		$junkmax = mt_rand( 0, 3 );
		$total = 4;
		do
		{
			$required[$total] = array();
			
			$req = mt_rand( 0, count( $arr ) - 1 );
			$reqnum = mt_rand( 1, ( $total - $junkmax ) );
			
			$dup = FALSE;
			foreach ( $required as $rkey => $rarr )
			{
				if ( isset( $rarr[$arr[$req]] ) || strcasecmp( $arr[$req], "junk" ) == 0 )
				{
					$dup = TRUE;
					break;
				}
			}
			
			if ( $dup == FALSE )
			{
				$required[$total][$arr[$req]] = $reqnum;
				
				$total -= $reqnum;
			}
		} while ( $total > $junkmax );
		
		return $required;
	}
	
	function get_correct_blocks()
	{
		require( "config.phpmeow.php" );
		
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
