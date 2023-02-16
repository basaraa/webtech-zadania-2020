<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Informácie o olympionikovi</title>
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
if (isset($_GET["idcko"]))
    $idckoo=$_GET["idcko"];
else
    $idckoo=1;
$idckoo=(int)$idckoo;
require_once "triedy/oh_hry.php";
require_once "triedy/databaza/Database.php";
try {
    $conn = (new Database())->Napojenie();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("
            SELECT osoby.name, osoby.surname, osoby.birth_day, osoby.birth_place, osoby.birth_country,
                   oh.type, umiestnenia.placing, umiestnenia.discipline, oh.year, oh.city
            FROM ((umiestnenia 
            join osoby ON osoby.id=umiestnenia.person_id)
            join oh ON oh.id=umiestnenia.oh_id) 
            where umiestnenia.person_id= '$idckoo'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS,"oh_hry");
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$over=0;
foreach($result as $hry){
    if ($over===0)
        echo "
                <p><span>Celé meno olympionika:</span> {$hry->getName()} {$hry->getSurname()}                 
                <p><span>Dátum narodenia:</span> {$hry->getBirthDay()}
                <p><span>Miesto narodenia:</span> {$hry->getBirthCountry()} - {$hry->getBirthPlace()}                                              
                <table class='tabulka' id='tabulka'>
                    <thead>
                        <tr>                          
                            <th>Typ olympiády</th>
                            <th>Rok</th>
                            <th>Miesto</th>
                            <th>Disciplína</th>     
                            <th>Umiestnenie</th>
                        </tr>
                    </thead>
                    <tbody>
        ";
    echo "<tr>
            <td>{$hry->getType()}</td>
            <td>{$hry->getYear()}</td>
            <td>{$hry->getCity()}</td>
            <td>{$hry->getDiscipline()}</td>
            <td>{$hry->getPlacing()}</td>
          </tr>
    ";
    $over=1;
}
  echo "</tbody></table>";
$conn = null;
?>
<div class="tlacidla">
    <button class="btn btn-primary" onclick="location.href='top_olymp.php'">Návrat naspäť</button>
</div>
<script src="javascript/scripts.js"></script>
</body>
</html>


