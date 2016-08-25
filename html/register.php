<?php # Script 18.6 - register.php
// This is the registration page for the site.
require ('includes/config.inc.php');
$page_title = 'Register';
include ('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	// Need the database connection:
	require (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $p = FALSE;
	
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$fn = mysqli_real_escape_string ($dbc, $trimmed['first_name']);
	} else {
		echo '<p class="error">Please enter your first name!</p>';
	}

	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	} else {
		echo '<p class="error">Please enter your last name!</p>';
	}
	
	// Check for an email address:
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	} else {
		echo '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($fn && $ln && $e && $p) { // If everything's OK...

		// Make sure the email address is available:
		$q = "SELECT id FROM users WHERE email='$e'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) { // Available.

			// Create the activation code:
			$a = md5(uniqid(rand(), true));

			// Add the user to the database:
			$q = "INSERT INTO users (email, pass, first_name, last_name, active, registration_date) VALUES ('$e', SHA1('$p'), '$fn', '$ln', '$a', NOW() )";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				// Send the email:
				$body = "Thank you for registering at <whatever site>. To activate your account, please click on this link:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@sitename.com');
				
				// Finish the page:
				echo '<h3>Thank you for registering! A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account.</h3>';
				include ('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
			
		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
		}
		
	} else { // If one of the data tests failed.
		echo '<p class="error">Please try again.</p>';
	}

	mysqli_close($dbc);

} // End of the main Submit conditional.
?>
	
<h1>Register</h1>
<form action="register.php" method="post">
	
	<div class="form-group">
		<label for="first_name">First Name:</label> 
		<input class="short" type="text" id="first_name" name="first_name" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" /></div>
	
	<div class="form-group">
		<label for="last_name">Last Name:</label> 
		<input class="short" type="text" id="last_name" name="last_name" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" /></div>

	<div class="form-group">
		<label for="email">Email Address:</label> 
		<input class="short" type="text" id="email" name="email" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /> </div>
		
	<div class="form-group">
		<label for="password1">Password:</label> 
		<input class="short" type="password" id="password1" name="password1" value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>" /> <br/>
		<small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small></div>

	<div class="form-group">
		<label for="password2">Confirm Password:</label> 
		<input class="short" type="password" id="password2" name="password2" value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" /></div>
	
	
		<button type="submit" class="btn btn-parkway" name="submit" value="Register" >Register</button>

</form>

<?php include ('includes/footer.html'); ?>