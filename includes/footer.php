<footer>
    <div class="gridfooter">
        <div class="leftline"></div>
        <div class="footerimg">
            <img src="bilder/logoblack.png" class="footerlogo" alt="Eltials Ragdoll Footer Logo">
        </div>
        <div class="rightline"></div>
    </div>

    <div class="footermiddle">
        <div class="widget">
            <div class="wp-container-624180221243e wp-block-group">
                <div class="wp-block-group__inner-container">
                    <h3>Kontakta oss gärna vid frågor</h3>
                    <p class="blackp">Använd nedan formulär eller maila oss på
                        eltials@ragdoll.com</p>

                    <?php
                    if (isset($_POST['submit'])) {
                        $to = "tildakallstrom@gmail.com";
                        $from = $_POST['eemail'];
                        $name = $_POST['nname'];
                        $subject = "Form submission";
                        $subject2 = "Copy of your form submission";
                        $message = $name . " wrote the following:" . "\n\n" . $_POST['message'];
                        $message2 = "<p>Ditt meddelande: " . $_POST['message'] . "</p>";

                        $headers = "From:" . $from;
                        $headers2 = "From:" . $to;
                        mail($to, $subject, $message, $headers);
                        mail($from, $subject2, $message2, $headers2);
                        echo "<p>Mailet är skickat. Tack " . $name . ", vi återkommer så snart vi kan.</p>";
                        echo $message2;
                    }
                    ?>
                    <form action="#" method="post" class="contactform">
                        <label for="nname" class="flabel">Ditt namn:</label><br> <input type="text" name="nname" id="nname" class="fname"><br>
                        <label for="eemail" class="flabel">Din email:</label><br> <input type="text" name="eemail" id="eemail" class="femail"><br>
                        <label for="message2" class="flabel">Ditt meddelenade:</label><br><textarea rows="5" name="message" id="message2" cols="30"></textarea><br><br>
                        <input type="submit" name="submit" value="Skicka meddelande" class="btnreg">
                    </form>


                </div>
            </div>
        </div>
        <div class="widget2">
            <h3>Hitta på webbplatsen</h3>
            <div class="footermenu">
                <a class="fmenu" href="index.html">Hem</a><br>
                <a class="fmenu" href="om-oss.html">Om oss</a><br>
                <a class="fmenu" href="vara-katter.html">Våra katter</a><br>
                <a class="fmenu" href="kattungar.html">Kattungar</a><br>
                <a class="fmenu" href="tidigare-katter.html">Tidigare katter</a><br>
            </div>

        </div>
    </div>
    <div class="socialmediafooter">
        <p class="whitecenterp">Följ oss på sociala medier:</p>
        <div class="socialmediafootergrid">
            <div>
                <img src="bilder/instagram.png" alt="Instagram" class="socialimg">
            </div>
            <div>
                <img src="bilder/facebook.png" alt="Facebook" class="socialimg">
            </div>
        </div>
    </div>
    <div class="bottomfooter">
        <p class="centerpblack">Webbplatsen är skapad av Tilda Källström <a href="https://tildakallstrom.se">https://tildakallstrom.se</a></p>
    </div>

    <script src='./script/script.js'></script>
    <script src='./script/menu.js'></script>
    <script src='./script/textexpand.js'></script>
    <script src='./script/hide.js'></script>
</footer>

</body>

</html>