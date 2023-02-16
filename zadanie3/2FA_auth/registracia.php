<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Editovanie</title>
    <link rel="shortcut icon" type="image/jpg" href="../img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="../index.php">Domov</a></li>
            <li><a href="registracia_form.php">Registrácia</a></li>
        </ul>
    </nav>
    <hr>
</header>

<?php
if (isset($_POST['Meno'])) {
    require_once 'PHPGangsta/GoogleAuthenticator.php';
    $secret_code=$_POST['secret_c'];
    $verify=$_POST['Verify_code'];
    $ga = new PHPGangsta_GoogleAuthenticator();
    $result = $ga->verifyCode($secret_code, $verify);
    if ($result===true){
        require_once "../triedy/reg_class.php";
        require_once "../triedy/databaza/Database.php";
        try {
            $conn = (new Database())->Napojenie();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $meno=$_POST['Meno'];
            $priezvisko=$_POST['Priezvisko'];
            $login=$_POST['Email'];
            $sql=("SELECT * from users where email='$login'");
            $result=$conn->query($sql);
            if (!($result->rowCount()>0)){
                $heslo=md5($_POST['Heslo']);
                $typ="normal";
                $sql = "INSERT INTO users (meno,priezvisko,email,heslo,fa_code,typ)
                    VALUES ('$meno','$priezvisko', '$login', '$heslo','$secret_code','$typ')";
                $conn->exec($sql);
                echo "<h2 class='nonerror'>Úspešna registrácia";
            }
            else
                echo "<h1 class='error'>Zlyhanie registrácie: Človek s takýmto loginom(emailom) už existuje</h1>";
        } catch(PDOException $e) {
            echo "<h2 class='error'>Registrácia bola neúspešná, Error: " . $e->getMessage();
        }
        $conn = null;
    }
    else{
        echo "<h2 class='error'>Zlý overovací kód, registrácia bola neúspešná";
    }
}
?>
<div class="tlacidla">
    <button class="btn btn-primary" onclick="location.href='../index.php'" type="button">
        Návrat späť na stránku
    </button>
</div>
</body>
</html>
