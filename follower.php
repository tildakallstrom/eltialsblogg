<?php
//Tilda Källström 2022
session_start();
include_once('config.php');
include('includes/header.php');
?>
<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php?message=2");
} else {
    // echo "<span class='loggedin'>Du är inloggad som " . $_SESSION['username'] . "</span>";
}
?>
<script src="https://cdn.ckeditor.com/4.16.0/basic/ckeditor.js"></script>
<div class="welcome">
    <h2 class='h1top'>Favoriter</h2>
    <button onclick="topFunction()" id="topBtn" title="Go to top"><img src="./bilder/top.png" alt="Till toppen"></button>
</div>
<div class='main1'>

    <div class="main34">
        <div class='left2'>
            <div class='searchforuser'>
                <form method="post" action="#">
                    <label for="searchcriteria">Sök efter användare: </label><br><input type="text" name="searchcriteria" id="searchcriteria" class="firstname"><br>
                    <input type="submit" value="Sök användare" name="search" class="btnsearch">
                </form>
            </div>

            <?php
            //searcg user
            if (isset($_POST['search'])) {
                if (!empty($_REQUEST['searchcriteria'])) {
                    $searchcriteria = $_POST['searchcriteria'];
                    $sql = "Select * from user where firstname or lastname or username like '%$searchcriteria%'";
                    $result = mysqli_query($conn, $sql);
                    //skriv ut hittade användare
                    if ($row = mysqli_fetch_array($result)) {
                        echo ' <div class="searchforuser"><p class="centerp"><a href="user.php?id=' . $row["id"] . '">' . $row["username"] . '</a><br>';
                        echo ' Förnamn: ' . $row['firstname'];
                        echo '<br> Efternamn: ' . $row['lastname'] . "</p></div>";
                    } else {
                        echo "<p class='centerp'>Kunde inte hitta någon användare med det namnet.</p>";
                    }
                }
            }
            ?>
        </div>
        <div class='right2'>
            <div class='searchforuser'>
                <form method="post" action="#">
                    <label for="searchcriteriacat">Sök efter katt: </label><br><input type="text" name="searchcriteriacat" id="searchcriteriacat" class="firstname"><br>
                    <input type="submit" value="Sök katt" name="searchcat" class="btnsearch">
                </form>
            </div>
            <?php
            //search for cat
            if (isset($_POST['searchcat'])) {
                if (!empty($_REQUEST['searchcriteriacat'])) {
                    $searchcriteriacat = $_POST['searchcriteriacat'];
                    $sql = "Select * from cat join user on user.id = cat.ownerid where cat.name like '$searchcriteriacat';";
                    $result = mysqli_query($conn, $sql);
                    //skriv ut hittade användare
                    if ($rowcat = mysqli_fetch_array($result)) {
                        echo ' <div class="searchforuser"><p class="centerp">Ägare: <a href="user.php?id=' . $rowcat["id"] . '">' . $rowcat["username"] . '</a><br>';
                        echo ' Katt: ' . $rowcat['name'];
                        echo '<br> Efter: ' . $rowcat['father'];
                        echo '<br> Under: ' . $rowcat['mother'];
                        echo '<br> Född: ' . $rowcat['birth'] . "</p></div>";
                    } else {
                        echo "<p class='centerp'>Kunde inte hitta någon katt med det namnet.</p>";
                    }
                }
            }
            ?>
        </div>
    </div>
    <div class="main33">
        <div class='left'>

            <h3 class="centerh3">Mina favoriter</h3>
            <?php

            $user = new Users();
            $userlist = $user->getFollowers();
            foreach ($userlist as $user) {
                //skriv ut användare
                echo "<div class='user'><img src=profileimg/" . $user['profileimg'] . " alt='Profilbild' class='profileimg'> <p class='userp'><a class='postuser' href='user.php?id=" . $user['id'] . "'>" . $user['firstname'] . " " . $user['lastname'] . "</a></p></div>";
            }
            ?>


        </div>



        <div class='right'>
            <h3 class="centerh3">Bloggposter</h3>
            <?php

            $blogpost = new Blogposts();
            $blogpostlist = $blogpost->getFollowedPosts();
            foreach ($blogpostlist as $post) {
                if ($post['img']) {
                    echo "<div class='post'><img src=profileimg/" . $post['profileimg'] . " alt='Profilbild' class='profileimg'> <p class='userp'><a class='postuser' href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . $post['lastname'] . "</a></p><p class='created'> " . $post['created'] . "</p>
        <h3><a href='blogpost.php?postid=" . $post['postid'] . "'>"  . $post['title'] . "</a></h3><p>" .
                        $post['content'] . "</p><figure><img class='blogimg' src='./uploads/" . $post['img'] . "' alt='blogbild' ></figure><p class='rightp'>" . $post['countcomments'] . " kommentarer</p></div>";
                } else {
                    echo "<div class='post'><img src=profileimg/" . $post['profileimg'] . " alt='Profilbild' class='profileimg'> <p class='userp'><a class='postuser' href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . $post['lastname'] . "</a></p><p class='created'> " . $post['created'] . "</p>
        <h3><a href='blogpost.php?postid=" . $post['postid'] . "'>"  . $post['title'] . "</a></h3><p>" .
                        $post['content'] . "</p><p class='rightp'>" . $post['countcomments'] . " kommentarer</p></div>";
                }
            }

            ?>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
?>