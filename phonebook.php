<?php
include "functions.php";    

$users = listUsers();
$countries = listCountries();

?>

<!DOCTYPE html>
<html>

<head>
    <title>User Phonebooks</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
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
        <?php
        $count = 1;
        foreach ($users as $user) {
            if ($user['published'] == 1) {
                echo "<ol start=\"$count\">";
                echo "<li>";
                echo "<h3>" . $user['firstname'] . " " . $user['lastname'] . "<button class='content-section-button' onclick='toggleDetails(this)'>View Details</button></h3>";

                // Display user's address information
                echo "<div class='user-info'>";
                echo "<div class='user-info-section'>";
                echo "<h4>Address</h4>";
                echo "<table>";
                echo "<tr><td>Address:</td><td>" . $user['address'] . "</td></tr>";
                echo "<tr><td>City:</td><td>" . $user['city'] . "</td></tr>";

                // Find the corresponding country name
                $countryName = '';
                foreach ($countries as $country) {
                    if ($country['id'] == $user['id']) {
                        $countryName = $country['name'];
                        break;
                    }
                }
            
                echo "<tr><td>Country:</td><td>" . $countryName . "</td></tr>";
                echo "</table>";
                echo "</div>";

                // Display user's phone numbers
                echo "<div class='user-info-section'>";
                echo "<h4>Phone Numbers</h4>";
                echo "<ul>";
                $phoneNumbers = listPhoneNumbers($user['id']);
                foreach ($phoneNumbers as $phoneNumber) {
                    if ($phoneNumber['masked'] == 0) {
                        echo "<li>" . $phoneNumber['number'] . "</li>";
                    } else {
                        echo "<li>***********</li>";
                    }
                }
                echo "</ul>";
                echo "</div>";

                // Display user's email addresses
                echo "<div class='user-info-section'>";
                echo "<h4>Email Addresses</h4>";
                echo "<ul>";
                $emailAddresses = listEmailAddresses($user['id']);
                foreach ($emailAddresses as $emailAddress) {
                    if ($emailAddress['masked'] == 0) {
                        echo "<li>" . $emailAddress['email'] . "</li>";
                    } else {
                        echo "<li>***********</li>";
                    }
                }
                echo "</ul>";
                echo "</div>";

                echo "</div>";
        
                echo "</li>";
                echo "</ol>";

                $count++;
            }
        }
        ?>
    </section>

    <script>
        function toggleDetails(button) {
        var userSection = button.parentNode.parentNode;
        var userInfo = userSection.querySelector('.user-info');
        if (userInfo.style.display === 'none' || userInfo.style.display === '') {
            userInfo.style.display = 'flex';
            button.innerHTML = 'Hide Details';
        } else {
            userInfo.style.display = 'none';
            button.innerHTML = 'View Details';
        }
    }
    </script>
</body>

</html>