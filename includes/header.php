<?php
//Tilda Källström 2022
include_once('config.php');
?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eltials Blogg</title> 
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="icon" type="image/png" href="bilder/favicon.png">
</head>

<body>
    <header>
        <div class='headerdesktop'>
            <a href="https://tildakallstrom.se/skola/eltialsblogg/index.php"><img src='bilder/logowhite.png' alt='Eltials Ragdoll Logo' class='logodesktop'></a>
            <div class="headermenu">
                <div>

                </div>
            </div>
            <div class='rightheader'>
                <nav class='rightnav'>
                    <ul>
                        <li class='meny'>
                            <a href="index.php">Hem</a>
                        </li>
                        <li class='meny'>
                            <a href="about.php">Om</a>
                        </li>

                        <?php
                        if (isset($_SESSION['username'])) {
                        ?>
                            <li class='meny'>
                                <a href="blog.php">Bloggen</a>
                            </li>
                            <li class='meny'><a href="follower.php">Sök användare</a></li>
                            <li class='meny'><a href="follower.php">Favoriter</a></li>
                            <li class='meny'><a href="profile.php">Skapa blogginlägg</a></li>

                        <?php
                        }
                        ?>
                        <?php
                        if (!isset($_SESSION['username'])) {
                        ?>
                            <li class='meny'><a href="login.php">Logga in</a></li>
                        <?php
                        }
                        ?>

                        <?php
                        if (isset($_SESSION['username'])) {
                            $username = $_SESSION['username'];
                        ?>
                            <li class='meny'><a href="profile.php">Min profil</a></li>
                            <li class='meny'><a href="logout.php">Logga ut</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </nav>
            </div>

        </div>

        <div class='headermobile'>
            <div class="mobile">
                <div class="ham" id="hamburger" onclick="hamburgermenu(this)">
                    <div class="lineone"></div>
                    <div class="linetwo"></div>
                    <div class="linethree"></div>
                </div>
            </div>

            <nav class="navbar">
                <ul>
                    <li class='meny'>
                        <a href="index.php">Hem</a>
                    </li>
                    <li class='meny'>
                        <a href="about.php">Om</a>
                    </li>

                    <?php
                    if (isset($_SESSION['username'])) {
                    ?>
                        <li class='meny'>
                            <a href="blog.php">Bloggen</a>
                        </li>
                        <li class='meny'><a href="follower.php">Sök användare</a></li>
                        <li class='meny'><a href="follower.php">Favoriter</a></li>
                        <li class='meny'><a href="profile.php">Skapa blogginlägg</a></li>

                    <?php
                    }
                    ?>
                    <?php
                    if (!isset($_SESSION['username'])) {
                    ?>
                        <li class='meny'><a href="login.php">Logga in</a></li>
                    <?php
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];
                    ?>
                        <li class='meny'><a href="profile.php">Min profil</a></li>
                        <li class='meny'><a href="logout.php">Logga ut</a></li>
                    <?php
                    }
                    ?>
                </ul>
            </nav>
            <div>
                <a href="index.php"><img src='bilder/logo111.png' alt='Eltials Ragdoll Logo' class='logomobile'></a>
            </div>
            <div class='rightheader'>
                <nav class='rightnav'>
                    <ul>
                        <?php
                        if (!isset($_SESSION['username'])) {
                        ?>
                            <li class='meny'><a href="login.php">Logga in</a></li>
                        <?php
                        }
                        ?>

                        <?php
                        if (isset($_SESSION['username'])) {
                        ?>
                            <li class='meny'><a href="profile.php">Profil</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
        <script>
            let navElement = document.getElementById("navigation");

            for (let i = 0; i < navElement.childElementCount; i++) {
                if (window.location.href.split("?")[0] === navElement.children[i].children[0].href.split("?")[0]) {
                    navElement.children[i].children[0].classList.add("current");
                }
            }
        </script>
    </header>

    <div class="hero-image">

        <div class="hero-text">
            <h1 class="whiteh1">Eltials Blogg</h1>

        </div>
    </div>