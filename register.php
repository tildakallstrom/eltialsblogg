<?php
//Tilda Källström 2022
include('includes/header.php');
if (!isset($_SESSION['username']) && (!isset($_SESSION['id']))) {
} else {
    header('Location: profile.php');
}
?>
<div class='welcome'>
    <h2 class='h1top'>Registrera användare</h2>
</div>
<div class='mainblog'>
    <?php
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($_POST["password"] >= 7) {
            if ($_POST["password"] === $_POST["confirm_password"]) {
                $users = new Users();
                if ($users->isUserNameTaken($username)) {
                    $message = "<p class='red'>Namnet är taget!</p>";
                } elseif ($users->isEmailTaken($email)) {
                    $message = "<p class='red'>Denna email finns redan!</p>";
                } else {
                    if ($users->registerUser($username, $firstname, $lastname, $email, $password)) {
                        $message = "<p class='message'>Användare skapad!</p><p class='message'> <a href='login.php' class='btnreg'>Logga in</a></p>";
                    } else {
                        $message = "<p class='red'>Fel vid skapande av användare!</p>";
                    }
                }
            } else {
                echo "<p class='red'>Lösenorden matchar inte</p>";
            }
        } else {
            echo "<p class='red'>Lösenordet måste innehålla minst 7 tecken.</p>";
        }
    }
    ?>
    <div class="loginform">
        <form method="post" class="regform">
            <?php
            if (isset($message)) {
                echo $message;
            }
            ?>
            <label for="username">Användarnamn:</label>
            <br>
            <input type="text" name="username" class="firstname" id="username" required>
            <br>
            <label for="firstname">Förnamn:</label>
            <br>
            <input type="text" name="firstname" class="firstname" id="firstname" required>
            <br>
            <label for="lastname">Efternamn:</label>
            <br>
            <input type="text" name="lastname" class="firstname" id="lastname" required>
            <br>
            <label for="email">Email:</label>
            <br>
            <input type="email" name="email" class="firstname" id="email" required>
            <br>
            <label for="password">Lösenord:</label>
            <br>
            <input type="password" name="password" class="firstname" id="password" required>
            <br>
            <label for="confirm_password">Lösenord igen:</label>
            <br>
            <input type="password" name="confirm_password" class="firstname" id="confirm_password" required>
            <br>
            <div class="labell"><br>
                <input type="checkbox" id="confirm" name="confirm" onchange="confirmSave()"><label for="confirm" class="confirmlabel">Jag godkänner lagring av personuppgifter.</label><br><br>
            </div>
            <input type="submit" value="Skapa användarkonto" class="btnreg" id="saveButton" disabled>
        </form>
    </div>
</div>
<script>
    function confirmSave() {
        if (document.getElementById("confirm").checked) {
            document.getElementById("saveButton").disabled = false;
        } else {
            document.getElementById("saveButton").disabled = true;
        }
    }
</script>
<?php
include('includes/footer.php');
?>