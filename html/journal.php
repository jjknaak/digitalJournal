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
echo '\'s SOAP Journal</h1>';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	require (MYSQL);


	$je = "SELECT * from journals WHERE user_id={$_SESSION['id']}";
	$result = @mysqli_query ($dbc, $je);

	$num = mysqli_num_rows($result);


	if ($num > 0) {  // If it ran OK, display the records.

		// Print how many entries there are:
	echo "<p>You currently have $num journal entries.</p>\n";

	// Table header.
	echo '<p class="scripture">~ Romans 12:11‐12, NIV ~<br/>
“Never be lacking in zeal, but keep your spiritual fervor, serving the Lord.<br/>
Be joyful in hope, patient in affliction, faithful in prayer.”</p>
<p class="scrollInstructions">Please scroll to see the rest of the table.</p>

<div class="table-responsive">
<table class="table table-striped">
	<thead class="thead-inverse">
	<tr>
	<th></th>
	<th></th>
		<th>Scripture</th>
		<th>Observe</th>
		<th>Apply</th>
		<th>Pray</th>
		<th>General</th>
	</tr>
	</thead>
	<tbody>
';
	
	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		echo '<tr><td><a href="edit.php?journal_id=' . $row['journal_id'] . '">Edit</a></td><td><a href="delete.php?journal_id=' . $row['journal_id'] . '">Delete</a></td><td>' . $row['scripture'] . '</td>
		<td>' . $row['observe'] . '</td><td>' . $row['apply'] . '</td><td>' . $row['pray'] . '</td><td>' . $row['general'] . '</td></tr>';
	}

	echo '</tbody></table></div>'; // Close the table.
	
	mysqli_free_result ($result); // Free up the resources.	

} else { // If no records were returned.

	echo '<p class="error">There are currently no registered users.</p>';

}

mysqli_close($dbc); // Close the database connection.


} // End of SUBMIT conditional.*/
?>








<?php include ('includes/footer.html'); ?>