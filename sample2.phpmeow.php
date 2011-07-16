<html>

<head>
<title>phpMeow - Example Form Page</title>
<script type="text/javascript" src="phpmeow.js"></script>
<link rel="stylesheet" type="text/css" href="phpmeow.css" />
</head>

<body>

<form name="whatever" id="whatever" action="sample.confirm.phpmeow.php" method="POST">

<center>

<div>

<h1 align="center" style="color: blue">Simple phpMeow Example</h1>
<h3 align="center" style="color: navy">Integration with antiquated, static positioning table elements (above submit button).</h3>

<!-- You can use tables, divs, etc.  Just make sure the phpMeow call itself is contained within an absolute-positioned div. -->

<table border="0" cellspacing="2" cellpadding="2">
<tr>
<td align="right"><b>Your name:</b>&nbsp; </td>
<td><input type="text" name="s_name" id="s_name" size="30" /></td>
</tr>
<tr>
<td align="right"><b>What're you lookin' at?!</b>&nbsp; </td>
<td><input type="text" name="s_look" id="s_look" size="30" /></td>
</tr>
<tr>
<td align="right" valign="top"><b>May I borrow your kidneys for a few hours?</b>&nbsp; </td>
<td>
<input type="radio" name="s_kidneys" id="s_kidneys" value="Yes" /> Yes.
<br />
<input type="radio" name="s_kidneys" id="s_kidneys" value="YES!" /> YES!
<br />
<input type="radio" name="s_kidneys" id="s_kidneys" value="I wasn't using them, anyway." /> I wasn't using them, anyway.
</td>
</tr>
</table>

</div>

<div style="position: absolute; left: 25%; margin-left: 50px">

<?php

require( "phpmeow.class.php" );

$phpmeow = new phpmeow( array( "phpmeow_submit_include" => TRUE, "phpmeow_submit_value" => "Give it a Try!" ) );
$phpmeow->main();

?>
</div>

</center>

</form>

<?php

$phpmeow->autofill();

?>
</body>

</html>
