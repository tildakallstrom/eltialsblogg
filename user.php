<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?message=2");
} else {
    //echo "<span class='loggedin'>Du är inloggad som " . $_SESSION['username'] . "</span>";
}
include_once('config.php');
include('includes/header.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header('Location: index.php');
}
?>
<div class="welcome">
    <?php
    $user = new Users();
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $profileimg = $_POST['profileimg'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $profile = $_POST['profile'];
    }
    //hämta användaren från id
    $show_user = $user->getUserFromId($id);
    $username = $show_user['username'];
    $profileimg = $show_user['profileimg'];
    $firstname = $show_user['firstname'];
    $lastname = $show_user['lastname'];
    $profile = $show_user['profile'];
    echo "<h2 class='h1top'>" . $username . "'s profil.</h2>";
    ?>
</div>
<div class="mainblog">
    <div class="profile">

        <?php
        $users = new Users();

       

        if ($_SESSION['id'] != $_GET['id']) {
            if (isset($_GET['id'])) {
                $username = $_SESSION['username'];
                $userid = $_GET['id'];
                //kolla om inloggad användare följer denna användare, isf skicka ut follow-knapp, annars inte
                if ($users->isThisFollowed($username, $userid)) {
                    echo '  <form id="unfollow" method="post" >
        <button type="submit" value="unfollow" name="unfollow" class="btnreg">Avfölj</button>
        </form>';
                } else {
                    echo "<form id='follow' method='post' >
        <button type='submit' value='follow' name='follow' class='btnreg'>Följ</button>
        </form>";
                }
            }
        }

        if (isset($_POST['follow'])) {
            if ($users->followUser($username, $userid)) {
                echo "<p class='follow'>Du följer nu denna användare.</p>";
            }
        }
        if (isset($_POST['unfollow'])) {
            if ($users->unfollowUser($username, $userid)) {
                echo "<p class='follow'>Du har avföljt denna användare.</p>";
            }
        }

        $show_user = $user->getUserFromId($id);
        $followers = $show_user['followers'];
        $firstname = $show_user['firstname'];
        echo "<span>" . $firstname . " har " . $followers . " följare.</span>";
        ?>
        <div class="gridprofile">
            <?php
            echo "<div><h3 class='cath33'>$firstname $lastname</h3><img class='catprofile' src=profileimg/" . $profileimg . " alt='Profilbild' >";
            echo "$profile </div>";
            ?>
            <div>
                <?php

                $cat = new Cats();

                $catlist = $cat->getCatsFromOwner();
                $ownerid = $_GET['id'];
                foreach ($catlist as $cat) {

                    if ($cat['ownerid'] == $_GET['id']) {
                        if ($cat['catimg']) {
                            echo "<h3 class='cath33'>" . $cat['name'] . "</h3><img class='catprofile' src='./uploads/cats/" . $cat['catimg'] . "'><p class='catp'>Född:" . $cat['birth'] . "</p><p class='catp'>Under: " . $cat['mother'] . " Efter: " . $cat['father'] . "</p><p class='catp'>" . $cat['merits'] . "</p>";
                        } else {
                            echo "<div class='ukat'><h3>" . $cat['name'] . "</h3>" . "<p class='catp'>Född:" . $cat['birth'] . "</p><p class='catp'>Under: " . $cat['mother'] . " Efter: " . $cat['father'] . "</p><p class='catp'>" . $cat['merits'] . "</p></div>";
                        }
                    }
                }



                ?>
            </div>
        </div>
    </div>
    <button onclick="topFunction()" id="topBtn" title="Go to top"><img src="./bilder/top.png" alt="Till toppen"></button>
    <?php
    $user = new Users();
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $profileimg = $_POST['profileimg'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $profile = $_POST['profile'];
    }
    $show_user = $user->getUserFromId($id);
    $username = $show_user['username'];
    $profileimg = $show_user['profileimg'];
    $firstname = $show_user['firstname'];
    $lastname = $show_user['lastname'];
    $profile = $show_user['profile'];
    echo "<h2 class='h2profile'>" . $username . "'s blogg.</h2>";
    ?>

    <?php
    $blogpost = new Blogposts();
    $blogpostslist = $blogpost->getBlogpostsFromThisAuthor();
    foreach ($blogpostslist as $post) {
        if ($post['authorid'] = $_GET['id']) {
            if ($post['img']) {
                // om posten har en bild:
                echo "<div class='post'><img src='./profileimg/" . $post['profileimg'] . "' class='profileimg' alt='profileimg' >" . "<p class='userp'>" . "<a href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . " " . $post['lastname'] . "</a></p>" . "<p class='created'>" . $post['created'] .
                    "</p><h3><a href='blogpost.php?postid=" . $post['postid'] . "'>" . $post['title'] . "</a></h3>" . $post['content'] . " <p><figure><img class='blogimg' src='./uploads/" . $post['img'] . "' alt='blogbild' ></figure><p class='rightp'>" . $post['countcomments'] . " kommentarer</p> </div> ";
            } else {
                // om posten inte har en bild:
                echo "<div class='post'><img src='./profileimg/" . $post['profileimg'] . "' class='profileimg' alt='profileimg' >" . "<p class='userp'>" . "<a href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . " " . $post['lastname'] . "</a></p>" . "<p class='created'>" . $post['created'] .
                    "</p><h3><a href='blogpost.php?postid=" . $post['postid'] . "'>" . $post['title'] . "</a></h3>" . $post['content'] . " <p><p class='rightp'>" . $post['countcomments'] . " kommentarer</p> </div> ";
            }
        }
    }

    ?>
</div>
<script>
    CKEDITOR.replace('content');
</script>
<script>
    CKEDITOR.replace('profile');
</script>
<?php
include('includes/footer.php');
?>
