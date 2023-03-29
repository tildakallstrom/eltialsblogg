<?php
//Tilda Källström 2022
include('includes/header.php');
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo "<div class='welcome'>
    <h2 class='h1top'>Hej $username!</h2>
</div>";
} else {
    echo "<div class='welcome'>
    <h2 class='h1top'>Bli medlem på Eltials kattdagbok -> <a href='register.php'>Bli medlem</a></h2>
</div>";
}
?>
<div class='main'>
    <div class='left'>
        <img src="bilder/cat1.jpg" alt="cat1" class="img">
    </div>
    <div class="right">
        <h2 class="centerh2">Eltials Katter</h2>
        <p class="p1">På denna webbplats kan du som har en katt från Eltials Ragdoll registrera dig för att använda bloggportalen.</p>
        <?php
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            echo "<div class='welcome'>
   <a class='herobtn' href='blog.php'>Läs de senaste inläggen >></a>
</div>";
        } else {
            echo "<div class='welcome'>
    <a class='herobtn' href='login.php'>Logga in</a>
</div>";
        }
        ?>
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
    </div>
</div>
<?php
include('includes/footer.php');
?>
