<?php
//Tilda Källström 2022 
session_start();
include_once('config.php');
include('includes/header.php');
?>
<?php
if (!isset($_SESSION['username']) && (!isset($_SESSION['id']))) {
    header("Location: login.php?message=2");
} else {
    // echo "<span class='loggedin'>Du är inloggad som " . $_SESSION['username'] . "</span>";
}
?>
<script src="https://cdn.ckeditor.com/4.16.0/basic/ckeditor.js"></script>
<div class="welcome">
    <?php
    if (!isset($_SESSION['username'])) {
        header("Location: login.php?message=2");
    } else {
        echo "<h2 class='h1top'>" . $_SESSION['username'] . "'s profil.</h2>";
    }
    ?>
</div>
<div class="main1">
    <?php
    $alert = new Alerts();
    $alertlist = $alert->getAlerts($username);
    $username = $_SESSION['username'];
    foreach ($alertlist as $alert) {
        if ($alert['author'] == $_SESSION['username']) {
            echo "<div class='alert'><a href='blogpost.php?postid=" . $alert['postid'] . "' class='alertbtn'>" . $alert['user'] . " kommenterade på " . $alert['title'] . '</a></div>';
        }
    }
    ?>
    <div class="main5">
        <div class="left1">
            <?php
            $user = new Users();
            $update_user = $user->getUserFromUsername($username);
            $profileimg = $update_user['profileimg'];
            ?>
            <h2 class="h2profile">Byt profilbild</h2>
            <form method="post" enctype="multipart/form-data" class="profilechange">
                <label for="profileimg">Profilbild:</label>
                <?php echo "<img src='./profileimg/" . $profileimg . "' class='profileimg' alt='profileimg'>" ?><br>
                <br><input type="file" name="profileimg" id="profileimg" /> <br><br>
                <input type="submit" name="uploadprofile" value='Byt profilbild' class="btnbig"><br><br>
            </form><br>
            <?php
            $user = new Users();
            if (isset($_POST['uploadprofile'])) {
                if ($user->updateProfileimg()) {
                    echo '<script type="text/javascript">
              alert("Uppladdning av profilbild lyckades.");
               window.location = "profile.php";
           </script>';
                } else {
                    echo "<p class='red'>Fel vid uppladdning</p>";
                }
            }
            ?>
            <h2 class='h2profile'>Mina katter</h2>
            <ul id="expander" class="expander">
                <li>
                    <button class="readMore" id="button1" aria-expanded="false" aria-controls="sect1" onclick="myFunctionFour()">
                        <span id="morefour"></span> <span id="myBtnfour">Se mina katter +</span><span id="dotsfour"></span></button>
                    <article class="textExpand" id="sect1" role="region" aria-live="assertive">
                        <?php
                        $cat = new Cats();
                        $catlist = $cat->getCatsFromOwner();
                        $userid = $_SESSION['username'];
                        foreach ($catlist as $cat) {
                            if ($cat['userid'] == $_SESSION['username']) {
                                if ($cat['catimg']) {
                                    echo "<div class='mykat'><h3 class='cath3'>" . $cat['name'] . "</h3><img class='catprofile' alt='catprofile' src='./uploads/cats/" . $cat['catimg'] . "'><a class='delete' href='profile.php?deletecatid=" . $cat['catid'] . "'>X</a><p class='catp'>Född:" . $cat['birth'] . "</p><p class='catp'>Under: " . $cat['mother'] . " Efter: " . $cat['father'] . "</p>" . $cat['merits'] . "<a class='btnupdate bt1' href='updatecat.php?catid=" . $cat['catid'] . "'>Uppdatera</a></div>";
                                } else {
                                    echo "<h3>" . $cat['name'] . "</h3>" . "<p class='catp'>Född:" . $cat['birth'] . "</p><p class='catp'>Under: " . $cat['mother'] . " Efter: " . $cat['father'] . "</p><p class='catp'>" . $cat['merits'] . "</p><a class='delete' href='profile.php?deletecatid=" . $cat['catid'] . "'>X</a>" .
                                        " <a class='btnupdate' href='updatecat.php?catid=" . $cat['catid'] . "'>Uppdatera</a>";
                                }
                            }
                        }
                        ?>
                    </article>
                </li>
            </ul>
            <?php
            $cat = new Cats();
            if (isset($_GET['deletecatid'])) {
                $catid = $_GET['deletecatid'];
                if ($cat->deleteCat($catid)) {
                    echo "<p>Katt raderad</p>";
                } else {
                    echo "<p>Fel vid radering</p>";
                }
            }
            $cat = new Cats();
            if (isset($_POST['name'])) {
                $ownerid = $_SESSION['id'];
                $userid = $_SESSION['username'];
                $name = $_POST['name'];
                $catimg = $_FILES['image']['name'];
                $birth = $_POST['birth'];
                $mother = $_POST['mother'];
                $father = $_POST['father'];
                $merits = $_POST['merits'];

                if ($cat->addCat($ownerid, $userid, $name, $catimg, $birth, $mother, $father, $merits)) {
                    echo "<p class='centerpp'>Katten har skapats!</p>";
                } else {
                    echo "<p class='errormessage'>Fel vid skapande av katt.</p>";
                }
            }
            ?>
            <h2 class="h2profile">Lägg till katt</h2>
            <form method="post" action="profile.php" enctype="multipart/form-data" class='profileform'>
                <label for="name">Namn: </label><br><input type="text" id="name" name="name" class="title"><br>
                <label for="birth">Födelsedatum: </label><br><input type="date" id="birth" name="birth" class="title"><br>
                <label for="mother">Mor: </label><br><input type="text" id="mother" name="mother" class="title"><br>
                <label for="father">Far: </label><br><input type="text" id="father" name="father" class="title"><br>
                <label for="merits"> Meriter: </label><br><textarea class="profile" id="merits" name="merits"></textarea><br>
                <label for="image">Bild:</label><br>
                <input type="file" name="image" id="image" />
                <input type="submit" value="Lägg till katt" name="upload" class="btnbig">
            </form>
        </div>
        <div class="right1">
            <h2 class="h2profile">Ändra profil</h2>
            <?php
            $users = new Users();
            if (isset($_POST['deleteuser'])) {
                if ($users->deleteUser($username)) {
                    session_destroy();
                    header("Location: login.php");
                }
            }
            ?> <form id='deleteuser' method='post'>
                <button type='submit' value='deleteuser' name='deleteuser' class='deleteu'>Radera Användare</button>
            </form>
            <?php
            $user = new Users();
            $update_user = $user->getUserFromUsername($username);
            $firstname = $update_user['firstname'];
            $lastname = $update_user['lastname'];
            $email = $update_user['email'];
            $profile = $update_user['profile'];
            ?>
            <form method="post" class="profileform">
                <label for="firstname">Förnamn</label><br>
                <input type="text" name="firstname" class="firstname" id="firstname" value="<?php echo $firstname; ?>" required /><br>
                <br> <label for="lastname">Efternamn</label><br>
                <input type="text" name="lastname" class="lastname" id="lastname" required value="<?php echo $lastname; ?>" /><br>
                <br> <label for="email">Email</label><br>
                <input type="email" name="email" class="email" id="email" required value="<?php echo $email; ?>" /><br>
                <br> <label for="profile">Profil</label><br>
                <textarea name="profile" class="profile" id="profile" rows="10" cols="48"><?php echo $profile; ?></textarea>
                <br>
                <input type="submit" name="submit" value='Ändra profil' class="btnbig"><br><br>
            </form>
            <?php
            $user = new Users();
            if (isset($_POST['submit'])) {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];
                $profile = $_POST['profile'];
                //uppdatera
                if ($user->updateUser($firstname, $lastname, $email, $profile)) {
                    echo "<p class='centerp'>Profilen är uppdaterad!</p>";
                } else {
                    echo "<p class='centerp'>Fel vid uppdatering</p>";
                }
            }
            ?>
        </div>
    </div>
    <h2 class="h2profile">Skapa blogginlägg</h2>
    <?php
    $blogpost = new Blogposts();
    if (isset($_GET['deleteid'])) {
        $postid = $_GET['deleteid'];
        if ($blogpost->deleteBlogpost($postid)) {
            echo "<p>Post raderad</p>";
        } else {
            echo "<p>Fel vid radering</p>";
        }
    }
    if (isset($_POST['title'])) {
        $author = $_SESSION['username'];
        $authorid = $_SESSION['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $img = $_FILES['image']['name'];
        if ($blogpost->addBlogpost($author, $authorid, $title, $content, $img)) {
            echo "<p class='centerpp'>Blogginlägg skapad!</p>";
        } else {
            echo "<p class='errormessage'>Fel vid uppladdning av inlägg.</p>";
        }
    }
    ?>
    <div class="middled">
        <form method="post" action="profile.php" enctype="multipart/form-data" class='profileform'>
            <label for="title">Titel: </label><br><input type="text" id="title" name="title" class="title"><br>
            <label for="content"> Brödtext: </label><br><textarea class="profile" id="content" name="content" rows="10" cols="48"></textarea><br>
            <label for="image">Bild:</label><br>
            <input type="file" name="image" id="image" />
            <input type="submit" value="Skapa blogginlägg" name="upload" class="btnbig">
        </form>
        <button onclick="topFunction()" id="topBtn" title="Go to top"><img src="./bilder/top.png" alt="Till toppen"></button>
        <?php
        $blogpost = new Blogposts();
        $blogpostslist = $blogpost->getBlogpostsFromAuthor();
        echo "<h2 class='h2profile'>Mina blogginlägg</h2>";
        $username = $_SESSION['username'];
        foreach ($blogpostslist as $post) {

            if ($post['author'] == $_SESSION['username']) {
                if ($post['img']) {
                    echo "<div class='postt'><a class='delete' href='profile.php?deleteid=" . $post['postid'] . "'>x</a><img src='./profileimg/" . $post['profileimg'] . "' class='profileimg' alt='profileimg' >" .
                        "<p class='userp'>" . "<a href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . " " . $post['lastname'] . "</a></p>"
                        . "<p class='created'>" . $post['created'] .
                        "<h3><a href='blogpost.php?postid=" . $post['postid'] . "'>" . $post['title'] . "</a></h3>" . $post['content'] . "<figure><img class='blogimg' src='./uploads/" . $post['img'] . "' alt='blogbild' ></figure><p class='rightp'>" . $post['countcomments'] . " kommentarer</p><p> 
                <a class='btnupdate' href='update.php?postid=" . $post['postid'] . "'>Uppdatera</a></p></div>";
                } else {
                    echo "<div class='postt'><a class='delete' href='profile.php?deleteid=" . $post['postid'] . "'>x</a><img src='./profileimg/" . $post['profileimg'] . "' class='profileimg' alt='profileimg' >" .
                        "<p class='userp'>" . "<a href='user.php?id=" . $post['id'] . "'>" . $post['firstname'] . " " . $post['lastname'] . "</a></p>"
                        . "<p class='created'>" . $post['created'] .
                        "<h3><a href='blogpost.php?postid=" . $post['postid'] . "'>" . $post['title'] . "</a></h3>" . $post['content'] . "<p class='rightp'>" . $post['countcomments'] . " kommentarer</p><p> 
                <a class='btnupdate' href='update.php?postid=" . $post['postid'] . "'>Uppdatera</a></p></div>";
                }
            }
        }
        ?>
    </div>
</div>
<script>
    CKEDITOR.replace('merits');
</script>
<script>
    CKEDITOR.replace('content');
</script>
<script>
    CKEDITOR.replace('profile');
</script>
<?php
include('includes/footer.php');
?>