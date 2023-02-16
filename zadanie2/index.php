<?php

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Praca s databazou</title>
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
<h2>Naši olympíjský víťazi</h2>
<table class="tabulka" id="tabulka">
    <thead>
    <tr>
        <th>Krstné meno</th>
        <th>Priezvisko
            <span onclick="zoradenie(1,false,0)" class="odkaz"><i class="bi bi-arrow-down"></i></span>
            <span onclick="zoradenie(1,true,0)" class="odkaz"><i class="bi bi-arrow-up"></i></span>
        </th>
        <th>Rok zisku
            <span onclick="zoradenie(2,false,1)" class="odkaz"><i class="bi bi-arrow-down"></i></span>
            <span onclick="zoradenie(2,true,1)" class="odkaz"><i class="bi bi-arrow-up"></i></span>
        </th>
        <th>Miesto konania</th>
        <th>Typ
            <span onclick="zoradenie(4,false,2)" class="odkaz"><i class="bi bi-arrow-down"></i></span>
            <span onclick="zoradenie(4,true,2)" class="odkaz"><i class="bi bi-arrow-up"></i></span>
        </th>
        <th>Disciplína</th>
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
            SELECT osoby.name, osoby.surname, oh.year, oh.city, oh.type, umiestnenia.discipline FROM ((umiestnenia 
            join osoby ON osoby.id=umiestnenia.person_id)
            join oh ON oh.id=umiestnenia.oh_id) 
            where placing=1");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_CLASS,"oh_hry");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        foreach($result as $hry){

            echo "<tr>
                    <td>{$hry->getName()}</td>
                    <td>{$hry->getSurname()}</td>
                    <td>{$hry->getYear()}</td>
                    <td>{$hry->getCity()}</td>
                    <td>{$hry->getType()}</td>
                    <td>{$hry->getDiscipline()}</td>
                    </tr>";
        }
        ?>
    </tbody>
</table>
<div class="tlacidla">
    <button class="btn btn-primary" onclick="zobraz_vrstvu(false)">
        Vytvoriť olympionika
    </button>
    <button class="btn btn-primary" onclick="zobraz_vrstvu2(false)">
        Vytvoriť umiestnenie
    </button>
</div>
<div id="modal_background"></div>
<div class="modal_div">
    <div id="modal_vrstva">
        <div class="konec">
            <button class="btn btn-primary" onclick="zobraz_vrstvu(true)">Zavrieť</button>
        </div>
        <h2>Vytvorenie olympionika</h2>
        <hr>
        <form action="insert.php" method="post" enctype="multipart/form-data">
            <label for="Meno">Zadaj krstne meno:</label><br>
            <input type="text" name="Meno" id="Meno" required><br>
            <label for="Priezvisko">Zadaj priezvisko:</label><br>
            <input type="text" name="Priezvisko" id="Priezvisko" required><br>
            <label for="Datum">Zadaj dátum narodenia:</label><br>
            <input type="text" name="Datum" id="Datum" required><br>
            <label for="Mesto">Zadaj mesto narodenia:</label><br>
            <input type="text" name="Mesto" id="Mesto" required><br>
            <label for="Stat">Zadaj štát v ktorom sa narodil:</label><br>
            <input type="text" name="Stat" id="Stat" required><br>
            <label for="Datum_D">Zadaj dátum smrti:</label><br>
            <input type="text" value="" name="Datum_D" id="Datum_D"><br>
            <label for="Mesto_D">Zadaj mesto smrti:</label><br>
            <input type="text" value="" name="Mesto_D" id="Mesto_D"><br>
            <label for="Stat_D">Zadaj štát v ktorom zomrel:</label><br>
            <input type="text" value="" name="Stat_D" id="Stat_D"><br>
            <div class="tlacidla">
                <input class="btn btn-primary" type="submit" value="Pridat olympionika" name="pridanie_sportovca" id="pridanie_sportovca">
            </div>
        </form>
    </div>
</div>
<div id="modal_background2"></div>
<div class="modal_div2">
    <div id="modal_vrstva2">
        <div class="konec">
            <button class="btn btn-primary" onclick="zobraz_vrstvu2(true)">Zavrieť</button>
        </div>
        <h2>Vytvorenie umiestnenia</h2>
        <hr>
        <form action="insert_umiestnenie.php" method="post" enctype="multipart/form-data">
            <label for="id_osoby">Vyber meno:</label><br>
            <select name="id_osoby" id="id_osoby">
                <?php
                $stmt = $conn->prepare("SELECT name, surname, id FROM osoby");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"oh_hry");
                    foreach($result as $hry){
                        echo "<option value='{$hry->getId()}'>{$hry->getName()} {$hry->getSurname()}</option>";
                    }
                ?>
            </select>
            <br>
            <label for="id_olymp">Vyber olympiádu:</label><br>
            <select name="id_olymp" id="id_olymp">
                <?php
                $stmt = $conn->prepare("SELECT id, type, year, city, country from oh");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"oh_hry");
                foreach($result as $hry){
                    echo "<option value='{$hry->getId()}'>{$hry->getYear()} {$hry->getType()} {$hry->getCity()} - {$hry->getCountry()}</option>";
                }
                $conn = null;
                ?>
            </select><br>
            <label for="Umiestnenie">Zadaj umiestnenie:</label><br>
            <input type="text" name="Umiestnenie" id="Umiestnenie" required><br>
            <label for="Disciplina">Zadaj disciplinu:</label><br>
            <input type="text" name="Disciplina" id="Disciplina" required><br>
            <div class="tlacidla">
                <input class="btn btn-primary" type="submit" value="Pridat umiestnenie" name="pridanie_umiestnenia" id="pridanie_umiestnenia">
            </div>
        </form>
    </div>
</div>
<script src="javascript/scripts.js"></script>
</body>
</html>

