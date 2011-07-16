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

function phpmeow_selblock( div )
{
	var infield = document.getElementById( "f" + div.id );
	if ( infield.value == 0 )
	{
		div.style.border = "10px solid yellow";
		infield.value = 1;
	}
	else
	{
		div.style.border = "10px solid navy";
		infield.value = 0;
	}
}

function phpmeow_openwindow( url, title )
{
	var windowname = "phpMeow - " + title;
	
	thiswindow = window.open( url, windowname, "status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=1,scrollbars=1,height=600,width=800" );
	thiswindow.moveTo( 100, 50 );
}
