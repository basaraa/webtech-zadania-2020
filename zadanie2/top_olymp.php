<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Top 10 olympionici</title>
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
<h2>Top 10 naších olympíjskych víťazov</h2>
<table class="tabulka" id="tabulka">
    <thead>
    <tr>
        <th>Celé meno</th>
        <th>Počet medailí</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    require_once "triedy/oh_hry.php";
    require_once "triedy/databaza/Database.php";
    try {
        $conn = (new Database())->Napojenie();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("
            SELECT umiestnenia.person_id,osoby.name, osoby.surname, count(umiestnenia.placing) as pocet_medaili FROM ((umiestnenia 
            join osoby ON osoby.id=umiestnenia.person_id)
            join oh ON oh.id=umiestnenia.oh_id) 
            where placing=1 group by umiestnenia.person_id, osoby.name, osoby.surname
            order by pocet_medaili desc
             limit 10") ;
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS,"oh_hry");
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    foreach($result as $hry){

        echo "<tr>
                    <td><a href='olymp_info.php?idcko={$hry->getPersonId()}'>{$hry->getName()} {$hry->getSurname()}</a></td>                                  
                    <td>{$hry->getPocetMedaili()}</td>
                    <td>
                    <a class='nodec' href='edit_form.php?idcko={$hry->getPersonId()}'>
                    <i class = 'bi bi-pencil-square'></i></a>
                    <a class='nodec' href='delete.php?idcko={$hry->getPersonId()}'>
                    <i class = 'bi bi-trash'></i></a>
                    </td>
                    </tr>";
    }
    $conn = null;
    ?>
    </tbody>
</table>
<script src="javascript/scripts.js"></script>
</body>
<?php
