<?php
//Tilda Källström 2022
include_once("config.php");
if (isset($_GET['postid'])) {
    $postid = $_GET['postid'];
} else {
    header('Location: index.php');
}
include('includes/header.php');
?>
<div class='welcome'>
    <h2 class='h1top'>Läs de senaste inläggen från våra användare</h2>
</div>
<div class="mainblog">
    <?php
    $blogpost = new Blogposts();

    if ($blogpost->countComments($postid)) {
    }

    if (isset($_POST['title'])) {
        $authorid = $_POST['authorid'];
        $profileimg = $_POST['profileimg'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $created = $_POST['created'];
        $img = $_POST['img'];
        $postid = $_GET['postid'];
    }
    $show_blogpost = $blogpost->getBlogpostFromId($postid);
    $authorid = $show_blogpost['authorid'];
    $author = $show_blogpost['author'];
    $profileimg = $show_blogpost['profileimg'];
    $firstname = $show_blogpost['firstname'];
    $lastname = $show_blogpost['lastname'];
    $title = $show_blogpost['title'];
    $content = $show_blogpost['content'];
    $created = $show_blogpost['created'];
    $img = $show_blogpost['img'];
    $postid = $show_blogpost['postid'];
    ?>
    <?php if ($img) {
        echo "<div class='postone'><img src=profileimg/" . $profileimg . " alt='Profilbild' class='profileimg'>
        <p class='userp'><a class='postuser' href='user.php?id=" . $authorid . "'>" . $firstname . " " . $lastname . "</a></p>
        <p class='created'> " . $created . "</p>
       <h3>"  . $title . "</h3> " . $content . " <figure><img class='blogimg' src='./uploads/" . $img . "' alt='blogbild' ></figure>
      </div>";
    } else {
        echo "<div class='postone'><img src=profileimg/" . $profileimg . " alt='Profilbild' class='profileimg'>
    <p class='userp'><a class='postuser' href='user.php?id=" . $authorid . "'>" . $firstname . " " . $lastname . "</a></p>
    <p class='created'> " . $created . "</p>
   <h3>"  . $title . "</h3>
   " . $content . " </div>";
    }

    $comment = new Comments();
    if (isset($_GET['deleteid'])) {
        $commentid = $_GET['deleteid'];
        if ($comment->deleteComment($commentid)) {
            echo "<p>Kommentar raderad</p>";
        } else {
            echo "<p>Fel vid radering</p>";
        }
    }
    if (!isset($_SESSION['username'])) {
    } else {
        echo '<form method="post" action="#" class="post1">
    <label for="message" id="messagel"> Kommentera: </label><br><textarea id="message" name="message" rows="10" cols="100"></textarea><br>
     <input type="submit" value="Kommentera" name="comment" class="btnreg11">
  </form> ';
    }
    if (isset($_POST['comment'])) {
        $username = $_SESSION['username'];
        $postid = $_GET['postid'];
        $message = $_POST['message'];
        if ($comment->addComment($username, $postid, $message)) {
            echo "<p class='centerpp'>Kommentar skapad!</p>";
        } else {
            echo "<p class='errormessage'>Fel vid kommentering</p>";
        }
    }
    $comment = new Comments();
    $commentslist = $comment->getComments();
    foreach ($commentslist as $comment) {
        if ($comment['postid'] == $_GET['postid']) {
            if ($_SESSION['username'] == $comment['user']) {
                echo "<div class='post'><a class='delete' href='blogpost.php?deleteid=" . $comment['commentid'] . "'>x</a><img src=profileimg/" . $comment['profileimg'] . " alt='Profilbild' class='profileimg'> <p class='userp'><a class='postuser' href='user.php?id=" . $comment['id'] . "'>" . $comment['firstname'] . " " . $comment['lastname'] . "</a></p><p class='created'> " . $comment['commented'] .
                    "</p><p>" . $comment['message'] . "</p></div>";
            } else {
                echo "<div class='post'><img src=profileimg/" . $comment['profileimg'] . " alt='Profilbild' class='profileimg'> <p class='userp'><a class='postuser' href='user.php?id=" . $comment['id'] . "'>" . $comment['firstname'] . $comment['lastname'] . "</a></p><p class='created'> " . $comment['commented'] .
                    "</p><p>" . $comment['message'] . "</p></div>";
            }
        }
    }
    ?>
</div>
<?php
include('includes/footer.php');
?>