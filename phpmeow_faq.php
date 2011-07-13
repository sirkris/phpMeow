<html>

<head>
<title>phpMeow - FAQ</title>
</head>

<body bgcolor="white" text="black" link="green" vlink="darkgreen" alink="blue">

<img src="phpmeow_logo.gif" alt="phpMeow" border="0" />

<br />

<h2 style="color: blue; font-family: Courier New">Frequently Asked Questions</h2>

<span style="font-size: 14pt">

<b style="color: red">Q. </b><b style="color: darkred">What <i>is</i> this?!</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">phpMeow is an image verification system designed to protect websites from automated bot 
attacks.  Based on an until-now theoretical concept known as KittenAuth, phpMeow is a refreshingly 
cute and fuzzy alternative to the increasingly annoying CAPTCHA image verification system.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">CAPTCHA?  What's that?  And why would I want there to be an alternative to it?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">This should answer both your questions:
<br />
<img src="worst_captcha.png" border="0" />
<br />
Look familiar?</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Are those image verification things getting harder to read, or am I just getting older?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">Both.  The fundamental problem with CAPTCHA is that it relies on an automated program's 
or script's (aka a "robot" or just "bot") inability to interpret linguistic symbols (i.e. letters) 
from an image.  Unfortunately, these bots have been getting more and more sophisticated and 
"intelligent" over the years.  Many are now able to correctly interpret even distorted letters 
from these images.  The makers of CAPTCHA and similar scripts are then forced to make the letters 
more and more difficult to make out, hoping to stay one step ahead of the bots.  This has the 
unfortunate side-effect of making it difficult-- and in many cases, even impossible-- for a 
legitimate human such as yourself to read it.  It's theoretically possible that, at some point, 
the bots will become better able to read these letters than humans, rendering CAPTCHA completely 
useless in the long-run.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Ok, so how is phpMeow different?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">Where CAPTCHA uses letters and numbers, phpMeow uses pictures of kittens and other 
animals to test your humanity.  Obviously, since your keyboard lacks "kitten" and "puppy" keys 
(though wouldn't that be awesome?", you can't type in the answer.  Instead, phpMeow prompts 
you to click the composite image blocks that match a certain requirement.  For example, 
"Click all the blocks that contain 2 kittens and 1 bird."</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Isn't that less secure?  I mean, couldn't a bot just use brute-force to guess the right answer?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">No.  While it is statistically easier to guess a series of binary switches than it is to guess 
an alphanumeric string, phpMeow contains a highly sophisticated series of security tracking functions 
designed to detect and circumvent random guessing.  Generally speaking, CAPTCHA implementations do not 
have this feature.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">How do these security functions work?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">phpMeow uses PHP's native session handling abilities to track every attempt, failed and otherwise.  
If there are a certain number of failed attempts in a certain period of time (the exact rules for this are 
easily configurable in the script to suit the web admin's liking), phpMeow will initiate an automated lockout, 
preventing any new attempts from succeeding for the duration of the lockout (default is 5 minutes).  The 
rules are designed to make it so that only robots will trigger the lockout.  In the unlikely event that a 
human triggers this, it will display a message in place of the image blocks, advising the user to wait a few 
minutes before trying again.  If another attempt is made during this lockout, it's probably a bot, and phpMeow 
reacts accordingly by expanding the lockout to 1 hour and creating a 24-hour "probation" period that's hidden 
from the user.  If even 1 failed attempt occurs during this 24-hour period, an automatic 1-hour lockout occurs 
and the probationary period will be reset at 24 hours.  So, because it's still highly improbable that a bot 
will guess correctly on any given attempt, it is a statistical certainty that any guessing BFG robot will 
get locked out before it's able to do any damage.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Couldn't the bot just get around this session-based tracking by spoofing the user agent or whatever?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">If you're worried about that, phpMeow has a configurable option to cross-reference all session data 
with the user's IP address, preventing this sort of spoofing from succeeding.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">If these robots can break CAPTCHA, couldn't they do the same with phpMeow?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">Not currently, no.  Alpha-numeric characters-- even distorted ones-- are a lot more consistent and easy 
to identify than a photo of an animal.  phpMeow also randomly varies the size of each block and imploys other 
image effects to prevent a bot from identifying a pattern.  However, it should still be relatively easy for a human 
to tell a kitten from a fish and a puppy from a bird.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Couldn't a really dedicated spammer create an inventory of all the images used by phpMeow and then simply 
compare any generated image blocks against this?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">Nope!  There's a few reasons why that wouldn't work.  First, the individual images are merged into a 
4-image composite "block" image, which is what is sent to the web browser.  The sizes of each of the 4 images within 
each block are randomly varied, in addition to the afore-mentioned distortion/static effects.  Which images go 
where in a given block is determined randomly.  Finally, if you look at the &lt;img&gt; tag itself, you'll notice 
that the filename consists of a lengthy, randomly-generated string, preventing any sort of cross-referencing.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">What about performance?  How does phpMeow measure up?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">phpMeow has been tested on both Linux and Windows, and more extensive performance tests and comparisons 
are planned.  Currently, there are no performance/lag issues indicated when tested on Ubuntu Linux running 
Apache.  The only speed issue encountered was when running Apache on Windows, though that's not at all surprising 
as mod_php on Windows suffers from a number of filesystem and other performance-related problems.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Is there <i>any</i> disadvantage to using phpMeow as opposed to CAPTCHA?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">The only one that comes to mind is that a CAPTCHA prompt usually takes less space on the page than 
phpMeow.  This is unavoidable, but well worth it.</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Is phpMeow easy to install into an existing website?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">Yes!  phpMeow is completely self-contained.  Just place one function call where you want it to go in 
your form and another function call at or near the top of the destination page the form points to, and you're 
good to go!</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">Is phpMeow free?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">Yes!  phpMeow is open-source, licensed under the WTFPLv3 license.  It's completely free and you're 
free to do pretty much whatever you want with it!</span>

<br />
<br />

<b style="color: red">Q. </b><b style="color: darkred">I'm sold!  Where can I get it?</b>
<br />
<b style="color: blue">A. </b><span style="color: navy">The project is officially maintained on Github.  You can find the download link on the repository 
page at:  <a href="http://www.github.com/sirkris/phpmeow" target="_blank">www.github.com/sirkris/phpmeow</a>.</span>

</span>

</body>

</html>
