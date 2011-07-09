<?php

require( "config.phpmeow.php" );

/* Initialize the session.  You can skip this if your script already does this on its own.  --Kris */
$session = new phpmeow_session();
$session->start();

/* Validate the phpMeow selections.  --Kris */
if ( !( phpmeow::validate() ) )
{
	phpmeow::fail_redirect();
	die();
}

/* We're done!  The rest of your script can go here.  --Kris */
print "<h1 style=\"color: green\">Success!!</h1>";

print "<table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">";

foreach ( $_POST as $postvar => $postval )
{
	if ( strcmp( substr( $postvar, 0, 8 ), "fphpmeow" ) == 0 
		|| strcmp( substr( $postvar, 0, 7 ), "phpmeow" ) == 0 )
	{
		continue;
	}
	
	print "<tr>";
	print "<td align=\"right\">";
	print "<b>" . $postvar . "</b>";
	print "</td>";
	print "<td>";
	print $postval;
	print "</td>";
	print "</tr>";
}

print "</table>";

print "<br /><br />";

print "[ <a href=\"" . $_POST["phpmeow_referer"] . "\">Try it Again!</a> ]";
