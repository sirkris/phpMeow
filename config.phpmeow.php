<?php

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

/*
 * Set height/width to whatever you like.  The higher they are, the easier they are to see.
 * The lower they are, the more you'll be able to fit on screen.  The more you can fit, 
 * the more secure it will be.
 * 
 * If you want the images to retain their original size (i.e. no resizing by the script), 
 * set both the height and width to 100.
 * 
 * Note - If you shrink the animals, you may need to tweak the upper div width in 
 * phpmeow::main() in phpmeow.class.php.
 * 
 * --Kris
 */
$phpmeow_animal_width = 80;
$phpmeow_animal_height = 80;

/* Directory containing the phpMeow images.  --Kris */
$phpmeow_animalsdir = "phpMeow_images";

/* Feel free to tweak these to your liking.  Represents number of random dots/lines.  --Kris */
$phpmeow_min_static = 2;
$phpmeow_max_static = 4;

/* How long can each static line be.  Minimum is 0 (a dot).  --Kris */
$phpmeow_static_max_length = 7;

/* The higher the tint max, the more discoloration there can be.  --Kris */
$phpmeow_tint_max = 50;

/* Probabilities.  The integer setting represents the divisor.  Ex:  A "3" for tint means there's a 1 in 3 chance of tinting being done on each image.  --Kris */
$phpmeow_tint_probability = 2;

$phpmeow_sketch_probability = 35;

$phpmeow_negative_probability = 25;

$phpmeow_blur_probability = 3;

$phpmeow_grayscale_probability = 7;

/* Sets how many blocks horizontally by how many vertically.  The more you have, the more secure you'll be.  --Kris */
$phpmeow_blocks_x = 4;
$phpmeow_blocks_y = 2;

/* How much padding (in pixels) between each block.  Don't forget to factor in the div border area!  --Kris */
$phpmeow_padding_x = 30;
$phpmeow_padding_y = 30;

/* Encryption method.  --Kris */
$phpmeow_enchash = "sha512";  // Currently supported:  md5, sha1, sha256, sha512

/* Set to FALSE if you don't want the option of overriding any of the variables in this config file (only applies to phpmeow class).  --Kris */
$phpmeow_allowoverride = TRUE;

/* Our plurals => singulars.  If you add any animals to the mix, make sure to add a corresponding entry here!  --Kris */
$phpmeow_singular = array();
$phpmeow_singular["birds"] = "bird";
$phpmeow_singular["fish"] = "fish";
$phpmeow_singular["kittens"] = "kitten";
$phpmeow_singular["puppies"] = "puppy";

/* STRONGLY RECOMMENDED that you leave this enabled!  Otherwise, you will have little protection against bots using BFG to get through.  --Kris */
$phpmeow_security_enable = TRUE;

/* Sets whether security functions will cross-reference IP address with session.  Enable only if session-based tracking isn't stopping a bot attack.  --Kris */
$phpmeow_security_use_ip = FALSE;

/* Determines the MINIMUM time (in seconds) between cleanings of ipban.phpmeow.ini.  --Kris */
$phpmeow_security_ipban_cleanup_wait = 300;

/* If you want to make the user wait a few seconds after a failed attempt to deter BFG bots, set this to any value (in seconds) greater than 0.  --Kris */
$phpmeow_security_failure_wait = 0;

/*
 * XXXX ---- Do not edit below this line! ---- XXXX
 */

/* Handle all the class includes in one place for the sake of simplicity.  --Kris */
require_once( "phpmeow.class.php" );
require_once( "imagedir.phpmeow.class.php" );
require_once( "animal.phpmeow.class.php" );
require_once( "block.phpmeow.class.php" );
require_once( "session.phpmeow.class.php" );
require_once( "encryption.phpmeow.class.php" );
require_once( "security.phpmeow.class.php" );

/* To keep the security timestamps consistent, accounting for CPU delay mid-script.  --Kris */
$phpmeow_cur_time = time();

/* The phpMeow version number.  First non-empty master commit starts at 1.0, then increment by +0.1 with each subsequent master commit on GitHub.  --Kris */
$phpmeow_version = "1.00b";
