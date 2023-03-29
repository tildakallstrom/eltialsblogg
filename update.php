<?php
//Tilda Källström 2022
include_once("config.php");
if (!isset($_SESSION['username'])) {
    header("Location: login.php?message=2");
} else {
    if (isset($_GET['postid'])) {
        $postid = $_GET['postid'];
    } else {
        header('Location: index.php');
    }
}
include('includes/header.php');
?>
<div class='welcome'>
    <h2 class='h1top'>Uppdatera inlägg</h2>
</div>
<div class="mainblog">
    <script src="https://cdn.ckeditor.com/4.16.0/basic/ckeditor.js"></script>
    <?php
    $blogpost = new Blogposts();
    $update_blogpost = $blogpost->getBlogpostFromId($postid);
    $title = $update_blogpost['title'];
    $content = $update_blogpost['content'];
    $author = $update_blogpost['author'];
    ?>
    <div class='post1'>
        <form method="post" enctype="multipart/form-data" class='createpost'>
            <lable for="title">Titel:</label><br> <input type="text" class="title" name="title" value="<?= $title; ?>"><br>
                <input type="hidden" name="postid" value="<?= $postid ?>">
                <lable for="content"> Brödtext:</label><br> <textarea id="content" name="content" rows="12" cols="50"><?= $content ?></textarea><br>
                    <input type="submit" value="Uppdatera inlägget" name="submit" class="btnreg">
        </form>
        <?php
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            $content = $_POST['content'];

            $_SESSION['username'] = $username;
            if ($username == $author) {
                if ($blogpost->updateBlogpost($title, $content, $postid)) {

                    echo "<p class='centerp'>Inlägget är uppdaterat!</p>";
                } else {
                    echo "<p class='centerp'>Fel vid uppdatering</p>";
                }
            } else {
                echo "<p class='centerp'>Du kan inte ändra andras inlägg.</p>";
            }
        }
        ?>
    </div>
</div>
<script>
    CKEDITOR.replace('content');
</script>
<?php
include('includes/footer.php');
?>