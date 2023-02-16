<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridanie osoby</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/Styles.css">
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="index.php">Domov</a></li>
            <li><a href="top_olymp.php">Top 10</a></li>
        </ul>
    </nav>
    <hr>
</header>

<?php
require_once "triedy/oh_hry.php";
require_once "triedy/databaza/Database.php";
try {
    $conn = (new Database())->Napojenie();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $meno=$_POST['Meno'];
    $priezvisko=$_POST['Priezvisko'];
    $datum=$_POST['Datum'];
    $mesto=$_POST['Mesto'];
    $stat=$_POST['Stat'];
    $datum_D=$_POST['Datum_D'];
    $mesto_D=$_POST['Mesto_D'];
    $stat_D=$_POST['Stat_D'];
    $sql=("SELECT name, surname from osoby where name='$meno' and surname='$priezvisko'");
    $result=$conn->query($sql);
    if ($result->rowCount()>0)
        echo"<p><span>Takáto osoba už existuje v databáze</span>";
    else{
    $sql = "INSERT INTO osoby (name,surname,birth_day,birth_place,birth_country,
                               death_day,death_place,death_country)
            VALUES ('$meno', '$priezvisko', '$datum', '$mesto', '$stat', '$datum_D', '$mesto_D', '$stat_D')";
    $conn->exec($sql);
    echo "<p>Úspešne pridaný záznam";
    }
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage();
}
$conn = null;
?>
<div class="tlacidla">
    <button class="btn btn-primary" onclick="location.href='index.php'" type="button">
        Návrat späť na stránku
    </button>
</div>
</body>
</html>
