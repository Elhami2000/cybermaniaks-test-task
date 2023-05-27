<?php
session_start();
$dbconn;
dbConnection();
function dbConnection()
{
    global $dbconn;
    $dbconn = mysqli_connect("localhost", 'root', '', 'cybermaniakstest');
    if (!$dbconn) {
        die("Deshtoi lidhja me DB" . mysqli_error($dbconn));
    }
}
function login($username, $password)
{
    global $dbconn;
    $sql = "SELECT id, firstname, lastname, address, city, country_id FROM users ";
    $sql .= " WHERE username='$username' AND password='$password'";
    $res = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($res) == 1) {
        $userData = mysqli_fetch_assoc($res);
        $user = array();
        $user['id'] = $userData['id'];
        $user['firstname'] = $userData['firstname'];
        $user['lastname'] = $userData['lastname'];
        $user['address'] = $userData['address'];
        $user['city'] = $userData['city'];
        $user['country_id'] = $userData['country_id'];
        $_SESSION['user'] = $user;

        header("Location: index.php");
    } else {
        $_SESSION['message'] = "Nuk ka usera me keto te dhena.";
    }
}

function logout()
{
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

function modifyUser($userId, $firstname, $lastname, $address, $city, $countryId, $published)
{
    global $dbconn;
    $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', address='$address', city='$city', country_id='$countryId', published='$published' WHERE id='$userId'";
    mysqli_query($dbconn, $sql);
}

function listUsers()
{
    global $dbconn;
    $sql = "SELECT users.id, users.firstname, users.lastname, users.address, users.city, countries.name, users.published ";
    $sql .= "FROM users ";
    $sql .= "LEFT JOIN countries ON users.country_id = countries.id ";
    $sql .= "WHERE users.published = 1"; // Filter only published users
    $res = mysqli_query($dbconn, $sql);
    $users = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $users[] = $row;
    }
    return $users;
}


function getUserById($userId)
{
    global $dbconn;
    $sql = "SELECT * FROM users WHERE id='$userId'";
    $result = mysqli_query($dbconn, $sql);
    $user = mysqli_fetch_assoc($result);
    return $user;
}

function listCountries()
{
    global $dbconn;
    
    $sql = "SELECT id, name FROM countries";
    $result = mysqli_query($dbconn, $sql);
    
    $countries = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $countries[] = $row;
    }
    
    return $countries;
}


function listPhoneNumbers($currentUserId)
{
    global $dbconn;
    
    $sql = "SELECT id, number, masked FROM phones WHERE user_id = $currentUserId";
    $result = mysqli_query($dbconn, $sql);
    
    $phoneNumbers = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $phoneNumbers[] = $row;
    }
    
    return $phoneNumbers;
}

function modifyPhoneNumber($phoneNumberId, $newNumber, $maskStatus)
{
    global $dbconn;
    
    $sql = "UPDATE phones SET number = '$newNumber', masked = $maskStatus WHERE id = $phoneNumberId";
    mysqli_query($dbconn, $sql);
}

function addEmptyPhoneNumber($userId) {
    global $dbconn;
    
    $sql = "INSERT INTO phones (number, user_id) VALUES ('', $userId)";
    mysqli_query($dbconn, $sql);
}

function getPhoneNumberById($phoneNumberId) {
    global $dbconn;
    
    $sql = "SELECT number FROM phones WHERE id = $phoneNumberId";
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    return $row['number'];
}

function listEmailAddresses($userId) {
    global $dbconn;

    $sql = "SELECT id, email, masked FROM emails WHERE user_id = $userId";
    $result = mysqli_query($dbconn, $sql);

    $emailAddresses = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $emailAddresses[] = $row;
    }

    return $emailAddresses;
}

function modifyEmailAddress($emailId, $newEmail, $maskStatus)
{
    global $dbconn;
    
    $sql = "UPDATE emails SET email = '$newEmail', masked = $maskStatus WHERE id = $emailId";
    mysqli_query($dbconn, $sql);
}

function addEmptyEmailAddress($userId) {
    global $dbconn;
    
    $sql = "INSERT INTO emails (email, user_id) VALUES ('', $userId)";
    mysqli_query($dbconn, $sql);
}

function getEmailAddressById($emailId) {
    global $dbconn;
    
    $sql = "SELECT email FROM emails WHERE id = $emailId";
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    return $row['email'];
}

function updateUserPublishStatus($userId, $publishStatus)
{
    global $dbconn;
    
    $sql = "UPDATE users SET published = $publishStatus WHERE id = $userId";
    mysqli_query($dbconn, $sql);
}
