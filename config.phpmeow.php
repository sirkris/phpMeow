<?php

/*
 * Set height/width to whatever you like.  The higher they are, the easier they are to see.
 * The lower they are, the more you'll be able to fit on screen.  The more you can fit, 
 * the more secure it will be.
 * 
 * If you want the images to retain their original size (i.e. no resizing by the script), 
 * set both the height and width to 100.
 * 
 * --Kris
 */
$phpmeow_animal_width = 75;
$phpmeow_animal_height = 75;

/* Directory containing the phpMeow images.  --Kris */
$phpmeow_animalsdir = "phpMeow_images";

/* Feel free to tweak these to your liking.  Represents number of random dots/lines.  --Kris */
$phpmeow_min_static = 2;
$phpmeow_max_static = 5;

/* How long can each static line be.  Minimum is 0 (a dot).  --Kris */
$phpmeow_static_max_length = 10;

/* The higher the tint max, the more discoloration there can be.  --Kris */
$phpmeow_tint_max = 50;

/* Probabilities.  The integer setting represents the divisor.  Ex:  A "3" for tint means there's a 1 in 3 chance of tinting being done on each image.  --Kris */
$phpmeow_tint_probability = 3;

$phpmeow_sketch_probability = 5;

$phpmeow_negative_probability = 20;

$phpmeow_blur_probability = 3;

$phpmeow_grayscale_probability = 7;

/* Sets how many blocks horizontally by how many vertically.  The more you have, the more secure you'll be.  --Kris */
$phpmeow_blocks_x = 4;
$phpmeow_blocks_y = 2;

/* How much padding (in pixels) between each block.  Don't forget to factor in the div border area!  --Kris */
$phpmeow_padding_x = 30;
$phpmeow_padding_y = 30;

/* Password encryption method.  --Kris */
$phpmeow_enchash = "sha512";  // Currently supported:  md5, sha1, sha256, sha512

/* Set to FALSE if you don't want the option of overriding any of the variables in this config file (only applies to phpmeow class).  --Kris */
$phpmeow_allowoverride = TRUE;

/* Our plurals => singulars.  If you add any animals to the mix, make sure to add a corresponding entry here!  --Kris */
$singular = array();
$singular["birds"] = "bird";
$singular["fish"] = "fish";
$singular["kittens"] = "kitten";
$singular["puppies"] = "puppy";


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

/* The phpMeow version number.  First non-empty master commit starts at 1.0, then increment by +0.1 with each subsequent master commit on GitHub.  --Kris */
$phpmeow_version = "0.00a";
