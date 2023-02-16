<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Obsah priečinka</title>
    <link rel="shortcut icon" type="image/jpg" href="../img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<header>
    <script src="../scripts/Scripts.js"></script>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="../index.php">Domov</a></li>
            <li><a href="registracia_form.php">Registrácia</a></li>
        </ul>
    </nav>
    <hr>
    <h2 class="error">Je nutné vyplniť každé pole, vrátane naskenovania QR kódu<br>
        následne treba zadať kód, ktorý vygeneruje mobilná aplikácia</h2>
    <form action="registracia.php" method="post" enctype="multipart/form-data">
        <label for="Meno">Zadaj meno:</label><br>
        <input class='form-control' type="text" name="Meno" id="Meno" required><br>
        <label for="Priezvisko">Zadaj priezvisko:</label><br>
        <input class='form-control' type="text" name="Priezvisko" id="Priezvisko" required><br>
        <label for="Email">Zadaj login(email):</label><br>
        <input class='form-control' type="email" name="Email" id="Email" required><br>
        <label for="Heslo">Zadaj heslo:</label><br>
        <input class='form-control' type="password" name="Heslo" id="Heslo" required><br>
        <?php
        require_once 'PHPGangsta/GoogleAuthenticator.php';
        $websiteTitle = 'MyWebsite';
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($websiteTitle, $secret);
        echo '<div id="QR"><p>Naskenuj následujúci QR code na prepojenie účtu s aplikáciou:<p><img alt ="code" src="'.$qrCodeUrl.'" /><br>
            <label for="Verify_code">Zadaj overovací kód z mobilnej aplikácie:</label><br>
            <input class="form-control" type="text" name="Verify_code" id="Verify_code" required><br>       
        </div>';
        $myCode = $ga->getCode($secret);
        $result = $ga->verifyCode($secret, $myCode, 1);
        ?>

        <input type="hidden" id="secret_c" name="secret_c" value="<?php echo $secret;?>">
        <div class="tlacidla">
            <button type="button" id="qr_cd" class="btn btn-primary" onclick="zobraz_div()">Zobrazit QR code</button><br>
            <input class="btn btn-primary" type="submit" value="Registrovať sa" name="registracia" id="registracia">
        </div>
    </form>
</header>

</body>
</html>
<?php
