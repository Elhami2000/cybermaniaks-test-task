<?php
include "functions.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Get the current user ID
$currentUserId = $_SESSION['user']['id'];

// Fetch the user information
$currentUser = getUserById($currentUserId);
$phoneNumbers = listPhoneNumbers($currentUserId);
$emailAddresses = listEmailAddresses($currentUserId);
$countries = listCountries();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $countryId = $_POST['countryId'];
    $publishContact = isset($_POST['publishContact']) ? 1 : 0;

    // Modify the user information
    modifyUser($currentUserId, $firstname, $lastname, $address, $city, $countryId, $publishContact);

    // Modify the phone numbers
    if (isset($_POST['phone'])) {
        foreach ($_POST['phone'] as $phoneNumberId => $newNumber) {
            $maskPhone = isset($_POST['mask']['phone'][$phoneNumberId]) ? 1 : 0;
            modifyPhoneNumber($phoneNumberId, $newNumber, $maskPhone);
        }
    }

    // Modify the email addresses
    if (isset($_POST['email'])) {
        foreach ($_POST['email'] as $emailId => $newEmail) {
            $maskEmail = isset($_POST['mask']['email'][$emailId]) ? 1 : 0;
            modifyEmailAddress($emailId, $newEmail, $maskEmail);
        }
    }

    // Update the publish status
    updateUserPublishStatus($currentUserId, $publishContact);

    // Update the current user
    $currentUser = getUserById($currentUserId);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Contact</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="heading">
        <h1>Phonebook</h1>
    </div>

    <div class="header-buttons">
        <?php
        if (!isset($_SESSION['user'])) {
            echo "<a href='login.php'><button>Login</button></a>";
            echo "<a href='phonebook.php'><button>Public Phonebook</button></a>";
        } else {
            echo "<a href='logout.php'><button>Log out</button></a>";
            echo "<a href='phonebook.php'><button>Public Phonebook</button></a>";
            echo "<a href='contact.php'><button>My Contact</button></a>";
        }
        ?>
    </div>

    <section class="content-section">
        <div class="heading" style="height:50px">
            <h1>My Contact</h1>
        </div>
        <form method="POST" action="">
            <div class="publish-contacts">
                <label for="publishContact">Publish my contact:</label>
                <input type="checkbox" id="publishContact" name="publishContact" <?php if ($currentUser['published'] == 1)
                    echo 'checked'; ?>>
            </div>
            <div class="cards-wrapper">
                <div class="info-card">
                    <h2>Contact</h2>
                    <div class="label-alignment">
                        <label for="firstname">First Name:</label>
                        <input type="text" name="firstname" value="<?php echo $currentUser['firstname']; ?>">
                    </div>
                    <div class="label-alignment">
                        <label for="lastname">Last Name:</label>
                        <input type="text" name="lastname" value="<?php echo $currentUser['lastname']; ?>">
                    </div>
                    <div class="label-alignment">
                        <label for="address">Address:</label>
                        <input type="text" name="address" value="<?php echo $currentUser['address']; ?>">
                    </div>
                    <div class="label-alignment">
                        <label for="city">City:</label>
                        <input type="text" name="city" value="<?php echo $currentUser['city']; ?>">
                    </div>
                    <div class="label-alignment">
                        <label for="countryId">Country:</label>
                        <select name="countryId">
                            <?php
                            // Retrieve the list of countries from the database
                            $countries = listCountries();
                            foreach ($countries as $country) {
                                $selected = ($currentUser['country_id'] == $country['id']) ? 'selected' : '';
                                echo '<option value="' . $country['id'] . '" ' . $selected . '>' . $country['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="info-card">
                    <h2>Phones</h2>
                    <?php
                    $phoneNumbers = listPhoneNumbers($currentUserId);
                    foreach ($phoneNumbers as $phoneNumber) {
                        echo '<div class="label-alignment">';
                        echo '<label for="phone_' . $phoneNumber['id'] . '"></label>';
                        
                        echo '<input type="text" name="phone[' . $phoneNumber['id'] . ']" value="' . htmlspecialchars($phoneNumber['number']) . '">';
                        echo '<input type="checkbox" name="mask[phone][' . $phoneNumber['id'] . ']" value="1"';
                        if ($phoneNumber['masked'] == 1) {
                            echo ' checked';
                        }
                        echo '>';
                        echo '</div>';
                    }
                    ?>

                    <div class="add-btn">
                        <button id="addPhoneBtn">Add</button>
                    </div>

                </div>
                <div class="info-card">
                    <h2>Emails</h2>
                    <?php
                    $emailAddresses = listEmailAddresses($currentUserId);
                    foreach ($emailAddresses as $email) {
                        echo '<div class="label-alignment">';
                        echo '<label for="email_' . $email['id'] . '"></label>';
                        echo '<input type="text" name="email[' . $email['id'] . ']" value="' . htmlspecialchars($email['email']) . '">';
                        echo '<input type="checkbox" name="mask[email][' . $email['id'] . ']" value="1"';
                        if ($email['masked'] == 1) {
                            echo ' checked';
                        }
                        echo '>';
                        echo '</div>';
                    }
                    ?>
                    <div class="add-btn">
                        <button id="addEmailBtn">Add</button>
                    </div>
                </div>
            </div>
            <div class="save-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </section>
    <script>
        document.getElementById('addPhoneBtn').addEventListener('click', function () {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        var phoneNumber = response.phoneNumber;
                        var phoneNumberId = response.phoneNumberId;

                        // Create a new div element for the phone number
                        var div = document.createElement('div');
                        div.className = 'label-alignment';
                        div.innerHTML = '<label for="phone_' + phoneNumberId + '"></label>' +
                            '<input type="text" name="phone_' + phoneNumberId + '" id="phone_' + phoneNumberId + '" value="' + phoneNumber + '">';

                        // Append the new phone number div to the container
                        var container = document.getElementById('phoneNumbersContainer');
                        container.appendChild(div);
                    } else {
                        console.error('Error: ' + xhr.status);
                    }
                }
            };

            // Make the AJAX request to add a new phone number
            xhr.open('POST', 'addPhoneNumber.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send();
        });

        document.getElementById('addEmailBtn').addEventListener('click', function () {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        var emailAddress = response.emailAddress;
                        var emailAddressId = response.emailAddressId;

                        // Create a new div element for the email address
                        var div = document.createElement('div');
                        div.className = 'label-alignment';
                        div.innerHTML = '<label for="email_' + emailAddressId + '"></label>' +
                            '<input type="text" name="email_' + emailAddressId + '" id="email_' + emailAddressId + '" value="' + emailAddress + '">';

                        // Append the new email address div to the container
                        var container = document.getElementById('emailAddressesContainer');
                        container.appendChild(div);
                    } else {
                        // Handle the error
                        console.error('Error: ' + xhr.status);
                    }
                }
            };

            // Make the AJAX request to add a new email address
            xhr.open('POST', 'addEmail.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send();
        });

    </script>
</body>

</html>