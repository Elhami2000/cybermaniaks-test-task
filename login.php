<?php
include "functions.php";
if(isset($_POST['login'])){
    login(
        $_POST['email'],
        $_POST['passwordi']);
}
?>

<!DOCTYPE html>
<html>
    <head>
    <title>Login</title>
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
        <div class="heading" style="height:50px">
            <h1>Login</h1>
        </div>
            <div class="login-section">
                <form id="login_form" class="" method="post">
                    <div class="label-alignment">
                        <label>Username:</label>
                        <input class="field_class" name="email" type="text">
                    </div>
                    <div class="label-alignment">
                        <label>Password:</label>
                        <input id="pass" class="field_class" name="passwordi" type="password">
                        
                    </div>
                    <button type="submit" name="login" class="submit_class" form="login_form" placeholder="Log in">Log in!</button>
                </form>
            </div>
        </section>
    </body>
</html>