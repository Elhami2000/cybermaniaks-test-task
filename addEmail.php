<?php
include "functions.php";

// Get the current user ID
$currentUserId = $_SESSION['user']['id'];

// Add a new empty email address for the user
$emailAddressId = addEmptyEmailAddress($currentUserId);

// Retrieve the newly added email address from the database
$emailAddress = getEmailAddressById($emailAddressId);

// Prepare the response as JSON
$response = array('emailAddressId' => $emailAddressId, 'emailAddress' => $emailAddress);
echo json_encode($response);
?>
