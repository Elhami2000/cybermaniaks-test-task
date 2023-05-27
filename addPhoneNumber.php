<?php
include "functions.php";

// Get the current user ID
$currentUserId = $_SESSION['user']['id'];

// Add a new empty phone number for the user
$phoneNumberId = addEmptyPhoneNumber($currentUserId);

// Retrieve the newly added phone number from the database
$phoneNumber = getPhoneNumberById($phoneNumberId);

// Prepare the response as JSON
$response = array('phoneNumberId' => $phoneNumberId, 'phoneNumber' => $phoneNumber);
echo json_encode($response);
?>
