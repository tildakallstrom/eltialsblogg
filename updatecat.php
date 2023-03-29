<?php
//Tilda Källström 2022
include_once("config.php");
if (!isset($_SESSION['username'])) {
    header("Location: login.php?message=2");
} else {
    if (isset($_GET['catid'])) {
        $catid = $_GET['catid'];
    } else {
        header('Location: index.php');
    }
}
include('includes/header.php');
?>
<div class='welcome'>
    <h2 class='h1top'>Uppdatera katt</h2>
</div>
<div class="mainblog">
    <script src="https://cdn.ckeditor.com/4.16.0/basic/ckeditor.js"></script>
    <?php
    $cat = new Cats();
    $update_cat = $cat->getCatFromId($catid);
    $name = $update_cat['name'];
    $birth = $update_cat['birth'];
    $mother = $update_cat['mother'];
    $father = $update_cat['father'];
    $merits = $update_cat['merits'];
    ?>
    <div class='post1'>
        <form method="post" enctype="multipart/form-data" class='createpost'>
            <label for="name">Namn: </label><br><input type="text" name="name" class="title" value="<?= $name; ?>"><br>
            <input type="hidden" name="catid" value="<?= $catid ?>">
            <label for="birth">Födelsedatum: </label><br><input type="date" name="birth" class="title" value="<?= $birth; ?>"><br>
            <label for="mother">Mor: </label><br><input type="text" name="mother" class="title" value="<?= $mother; ?>"><br>
            <label for="father">Far: </label><br><input type="text" name="father" class="title" value="<?= $father; ?>"><br>
            <label for="merits"> Meriter: </label><br><textarea class="profile" name="merits" rows="10" cols="48"> <?= $merits; ?> </textarea><br>
            <input type="submit" value="Uppdatera katten" name="submit" class="btnreg">
        </form>
        <?php
        $cat = new Cats();
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $birth = $_POST['birth'];
            $mother = $_POST['mother'];
            $father = $_POST['father'];
            $merits = $_POST['merits'];
            if ($cat->updateCat($name, $birth, $mother, $father, $merits, $catid)) {
                echo "<p class='centerp'>Katten har uppdaterats!</p>";
            } else {
                echo "<p class='centerp'>Fel vid uppdatering</p>";
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