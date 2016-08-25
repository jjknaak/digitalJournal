<?php # Script 18.5 - index.php
// This is the main page for the site.

// Include the configuration file:
require ('includes/config.inc.php'); 

// Set the page title and include the HTML header:
$page_title = 'Welcome to this Site!';
include ('includes/header.html');

// Welcome the user (by name if they are logged in):
echo '<h1>Welcome';
if (isset($_SESSION['first_name'])) {
	echo ", {$_SESSION['first_name']}";
}
echo '!  What is SOAP Journaling?</h1>';
?>

<h2>Scripture</h2>
<p>Take today's Bible reading, pray over it, and then read
it through slowly. God will direct your attention to
certain verses or sections. Write these down in your
journal and focus your study there.</p>
<h2>Observe</h2>
<p>What is God showing you in this passage? Jot down in
your journal any words or events that stand out. Are
there any truths God wants you to learn? Warnings?
Commands? Guiding principles? Record those in your
journal. Now, what is the overall message God has for
you in this passage?</p>
<h2>Apply</h2>
<p>Now it's time to get personal. How does this affect
your life? Does God have instruction for you today?
Encouragement? Correction? Write down in your
journal God's personal message to you.</p>
<h2>Pray</h2>
<p>Prayer is a two‐way conversation with God. You may
find that, as you pray over what God has shown you,
He will reveal even more that He is wanƟng to show.
Be listening and journal your prayers!</p>




<?php include ('includes/footer.html'); ?>