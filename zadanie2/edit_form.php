<!DOCTYPE html>

<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Editovací formulár</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/Styles.css">
</head>
<?php
if (isset($_GET["idcko"]))
    $idckoo=$_GET["idcko"];
else
    $idckoo=1;
?>
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
<form action="edit.php?idcko=<?php echo"$idckoo";?>" method="post" enctype="multipart/form-data">
<?php
require_once "triedy/oh_hry.php";
require_once "triedy/databaza/Database.php";

try {
    $conn = (new Database())->Napojenie();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("
            SELECT name, surname, birth_day, birth_place, birth_country, death_day, death_place, death_country FROM osoby 
            where id='$idckoo'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS,"oh_hry");
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
foreach($result as $hry){

    echo "  <label for='Meno'>Krstné meno:</label><br>
            <input class='form-control' type='text' value='{$hry->getName()}' name='Meno' id='Meno' required><br>
            <label for='Priezvisko'>Priezvisko:</label><br>
            <input class='form-control' type='text' value='{$hry->getSurname()}' name='Priezvisko' id='Priezvisko' required><br>
            <label for='Datum'>Dátum narodenia:</label><br>
            <input class='form-control' type='text' value='{$hry->getBirthDay()}' name='Datum' id='Datum' required><br>
            <label for='Mesto'>Mesto narodenia:</label><br>
            <input class='form-control' type='text' value='{$hry->getBirthPlace()}' name='Mesto' id='Mesto' required><br>
            <label for='Stat'>Štát v ktorom sa narodil:</label><br>
            <input class='form-control' type='text' value='{$hry->getBirthCountry()}' name='Stat' id='Stat' required><br>
            <label for='Datum_D'>Dátum smrti:</label><br>
            <input class='form-control' type='text' value='{$hry->getDeathDay()}' name='Datum_D' id='Datum_D'><br>
            <label for='Mesto_D'>Mesto smrti:</label><br>
            <input class='form-control' type='text' value='{$hry->getDeathPlace()}' name='Mesto_D' id='Mesto_D'><br>
            <label for='Stat_D'>Štát v ktorom zomrel:</label><br>
            <input class='form-control' type='text' value='{$hry->getDeathCountry()}' name='Stat_D' id='Stat_D'>                                                                                                                                                              
         ";
}
?>
    <div class="tlacidla">
        <input class="btn btn-primary" type="submit" value="Potvrdiť údaje" name="uprava_sportovca" id="uprava_sportovca">
    </div>
</form>
<div class="tlacidla">
    <button class="btn btn-primary" onclick="location.href='top_olymp.php'" type="button">
        Návrat späť na stránku
    </button>
</div>


</body>
</html>
