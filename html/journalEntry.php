<?php # Script 18.8 - login.php
// This is the login page for the site.
require ('includes/config.inc.php'); 
$page_title = 'Journal';
include ('includes/header.html');

// Welcome the user (by name if they are logged in):
echo '<h1>';
if (isset($_SESSION['first_name'])) {
	echo "{$_SESSION['first_name']}";
}
echo '\'s Daily Entry</h1>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //handle form
	
	// Need the database connection:
	require (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$s = $o = $a = $p = $g = FALSE;

	// Check for a scripture:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['scripture'])) {
		$s = mysqli_real_escape_string ($dbc, $trimmed['scripture']);
	} else {
		echo '<p class="error">Please enter your Scripture</p>';
	}

	// Check for a observe:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['observe'])) {
		$o = mysqli_real_escape_string ($dbc, $trimmed['observe']);
	} else {
		echo '<p class="error">Please enter your Observation</p>';
	}

	// Check for a apply:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['apply'])) {
		$a = mysqli_real_escape_string ($dbc, $trimmed['apply']);
	} else {
		echo '<p class="error">Please enter how you will apply this</p>';
	}

	// Check for a pray:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['pray'])) {
		$p = mysqli_real_escape_string ($dbc, $trimmed['pray']);
	} else {
		echo '<p class="error">Please enter how you can pray about this</p>';
	}

	// Check for a pray:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['general'])) {
		$g = mysqli_real_escape_string ($dbc, $trimmed['general']);
	} else {
		echo '<p class="error">Please enter a general journal</p>';
	}

	$uid = $_SESSION['id'];

	
if ($s && $o && $a && $p) { // If everything's OK...

	$q = "INSERT INTO journals (user_id, date, scripture, observe, apply, pray, general) VALUES ('$uid', NOW(), '$s', '$o', '$a', '$p', '$g' )";
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
	if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
// Send an email, if desired.
			echo '<h3>Your entry has been saved.</h3>
			<p>Click <a href="journalEntry.php">HERE</a> to write another entry.</p>';
			mysqli_close($dbc); // Close the database connection.
			include ('includes/footer.html'); // Include the HTML footer.
			exit();
			
		} else { // If it did not run OK.
		
			echo '<p class="error">Your entry was not saved. Please make sure all fields were filled out correctly.  Contact the system administrator if you think an error occurred.</p>'; 

		}

} else { // Failed the validation test.
		echo '<p class="error">Please try again.</p>';		
	}
	
	mysqli_close($dbc); // Close the database connection.
	} // End of SUBMIT conditional.
?>

<p class="scripture">~ Romans 12:11‐12, NIV ~<br/>
“Never be lacking in zeal, but keep your spiritual fervor, serving the Lord.<br/>
Be joyful in hope, patient in affliction, faithful in prayer.”</p>

<form action="journalEntry.php" method="post" class="journal">
	
		<p>* Required</p>
		<div class="form-group">
			<label for="scripture">* Scripture:</label> 
			<textarea class="form-control" id="scripture" rows="1" name="scripture"></textarea>
		</div>
		<div class="form-group">
			<label for="observe">* Observe:</label> 
			<textarea class="form-control" id="observe" rows="10" name="observe"></textarea>
		</div>
		<div class="form-group">
			<label for="apply">* Apply:</label> 
			<textarea class="form-control" id="apply" rows="10" name="apply"></textarea>
		</div>
		<div class="form-group">
			<label for="pray">* Pray:</label> 
			<textarea class="form-control" id="pray" rows="10" name="pray"></textarea>
		</div>
		<div class="form-group">
			<label for="general">Gernal Journal:</label> 
			<textarea class="form-control" id="general" rows="10" name="general"></textarea>
		</div>

		<button type="submit" class="btn btn-parkway" name="submit" value="Save">Save</button>
	
</form>



<?php include ('includes/footer.html'); ?>