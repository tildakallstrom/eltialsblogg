<?php
//Tilda Källström 2022 
//start session
session_start();
include_once('config.php');


if (!isset($_SESSION['username']) && (!isset($_SESSION['id']))) {
    header("Location: login.php?message=2");
} else {
    // echo "<span class='loggedin'>Du är inloggad som " . $_SESSION['username'] . "</span>";
}
include('includes/header.php');
?>
<div class='welcome'>
    <h2 class='h1top'>Läs de senaste inläggen från våra användare</h2>
</div>

<!-- to the top button -->
<button onclick="topFunction()" id="topBtn" title="Go to top"><img src="./bilder/top.png" alt="Till toppen"></button>



<div class='main'>
    <div class='left'>
        <h2 class="centerh2">De senaste inläggen:</h2>
        <?php
        $blogpost = new Blogposts();
        $blogpostlist = $blogpost->getBlogposts();
        //skriv ut blogposter
        foreach ($blogpostlist as $post) {
            if ($post['img']) {
                echo "<div class='post'><img src=profileimg/" . $post['profileimg'] . " alt='Profilbild' class='profileimg'> <p class='userp'><a class='postuser' href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . " " . $post['lastname'] . "</a></p><p class='created'> " . $post['created'] . "</p>
        <h3><a href='blogpost.php?postid=" . $post['postid'] . "'>"  . $post['title'] . "</a></h3> " .
                    $post['content'] . "<figure><img class='blogimg' src='./uploads/" . $post['img'] . "' alt='blogbild' ></figure><p class='rightp'>" . $post['countcomments'] . " kommentarer</p></div>";
            } else {
                echo "<div class='post'><img src=profileimg/" . $post['profileimg'] . " alt='Profilbild' class='profileimg'> <p class='userp'><a class='postuser' href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . " " . $post['lastname'] . "</a></p><p class='created'> " . $post['created'] . "</p><h3><a href='blogpost.php?postid=" . $post['postid'] . "'>"  . $post['title'] . "</a></h3>" .
                    $post['content'] . "
        <p class='rightp'>" . $post['countcomments'] . " kommentarer</p></div>";
            }
        }
        ?>
    </div>
    <div class='right'>
        <h2 class="centerh2">Våra användare:</h2>
        <?php
        $user = new Users();
        $userlist = $user->getUsers();
        //hämta användare och skriv ut
        foreach ($userlist as $user) {
            echo "<div class='user'><img src=profileimg/" . $user['profileimg'] . " alt='Profilbild' class='profileimg'>
    <p class='userp'> <a class='userlink' href='user.php?id=" . $user['id'] . "'>" . $user['firstname'] . " " . $user['lastname'] . "</a></p></div>";
        }
        ?>
        <div class="down">
            <h2 class="centerh2">Användarnas katter:</h2>
            <?php

            $cat = new Cats();
            $catlist = $cat->getCats();
            //hämta användare och skriv ut
            foreach ($catlist as $cat) {
                echo "<div class='user'><img src=uploads/cats/" . $cat['catimg'] . " alt='Katt' class='profileimg'>
    <p class='userp'>" . $cat['name'] . "</p>
    <p>Ägare: <a class='userlink' href='user.php?id=" . $cat['id'] . "'>" . $cat['firstname'] . " " . $cat['lastname'] . "</a></p></div>";
            }
            ?>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
?>
