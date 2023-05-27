<?php
include "functions.php";
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

    </section>
</body>

</html>