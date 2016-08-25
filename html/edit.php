<?php

/*
EDIT.PHP
Allows user to edit specific entry in database
*/

// creates the edit record form
// since this form is used multiple times in this file, I have made it a function that is easily reusable
function renderForm($journal_id, $scripture, $observe, $apply, $pray, $general, $error) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Edit Entry</title>
	</head>
	<body>
		<?php
			// if there are any errors, display them
			if ($error != '') {
				echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
			}
		?>
		<form action="" method="post" class="journal">
			<input type="hidden" name="id" value="<?php echo $journal_id; ?>"/>

					<p>* Required</p>
					<div class="form-group">
						<label for="scripture">* Scripture: </label> 
						<input type="text" class="form-control" id="scripture" name="scripture" value="<?php echo $scripture; ?>"></input>
					</div>
					<div class="form-group">
						<label for="observe">* Observe: </label> 
						<input type="text" class="form-control" id="observe" name="observe" value="<?php echo $observe; ?>"></input>
					</div>
					<div class="form-group">
						<label for="apply">* Apply: </label> 
						<input type="text" class="form-control" id="apply" name="apply" value="<?php echo $apply; ?>"></input>
					</div>
					<div class="form-group">
						<label for="pray">* Pray: </label> 
						<input type="text" class="form-control" id="pray" name="pray" value="<?php echo $pray; ?>"></input>
					</div>
					<div class="form-group">
						<label for="general">General Journaling: </label> 
						<input type="text" class="form-control" id="general" name="general" value="<?php echo $general; ?>"></input>
					</div>

					<input type="submit" name="submit" value="Submit">
		</form>
	</body>
</html>

<?php

}



require ('includes/config.inc.php'); 
$page_title = 'Edit';
include ('includes/header.html');

// Welcome the user (by name if they are logged in):
echo '<h1> Edit ';
if (isset($_SESSION['first_name'])) {
	echo "{$_SESSION['first_name']}";
}
echo '\'s SOAP Journal Entry</h1>';

require (MYSQL);

// check if the form has been submitted. If it has, process the form and save it to the database
if (isset($_POST['submit'])) {

// confirm that the 'id' value is a valid integer before getting the form data
if (is_numeric($_POST['journal_id'])) {

// get form data, making sure it is valid
$id = $_POST['journal_id'];

$scripture = mysql_real_escape_string(htmlspecialchars($_POST['scripture']));
$observe = mysql_real_escape_string(htmlspecialchars($_POST['observe']));
$apply = mysql_real_escape_string(htmlspecialchars($_POST['apply']));
$pray = mysql_real_escape_string(htmlspecialchars($_POST['pray']));
$general = mysql_real_escape_string(htmlspecialchars($_POST['general']));

// check that firstname/lastname fields are both filled in
if ($scripture == '' || $observe == '' || $apply == '' || $pray == '') {

// generate error message
$error = 'ERROR: Please fill in all required fields!';

//error, display form
renderForm($id, $scripture, $observe, $apply, $pray, $general, $error);

} else {

// save the data to the database
mysql_query("UPDATE journals SET scripture='$scripture', observe='$observe', apply='$apply', pray='$pray', general='$general' WHERE journal_id='$journal_id'")
or die(mysqli_error());

// once saved, redirect back to the view page
header("Location: journal.php");
}
} else {

// if the 'id' isn't valid, display an error
echo 'Error!';
}
} else {// if the form hasn't been submitted, get the data from the db and display the form

// get the 'id' value from the URL (if it exists), making sure that it is valid (checing that it is numeric/larger than 0)
if (isset($_GET['journal_id']) && is_numeric($_GET['journal_id']) && $_GET['journal_id'] > 0) {
// query db


$id = $_GET['journal_id'];
$user_id = $_SESSION['id'];

$jq = "SELECT * FROM journals WHERE journal_id=$id";

$result = @mysqli_query ($dbc, $jq)
or die(mysqli_error());

$row = @mysqli_fetch_array ($result);

// check that the 'id' matches up with a row in the databse

if($row) {

// get data from db

$scripture = $row['scripture'];
$observe = $row['observe'];
$apply = $row['apply'];
$pray = $row['pray'];
$general = $row['general'];

// show form
renderForm($id, $scripture, $observe, $apply, $pray, $general, '');

} else {// if no match, display result

echo "No results!";

}

} else { // if the 'id' in the URL isn't valid, or if there is no 'id' value, display an error


echo 'Error!';

}

}


?>