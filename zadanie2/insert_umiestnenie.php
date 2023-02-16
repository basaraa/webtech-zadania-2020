<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridanie umiestnenia</title>
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
    $osobaa=$_POST['id_osoby'];
    $olympiada=$_POST['id_olymp'];
    $umiestnenie=$_POST['Umiestnenie'];
    $disciplina=$_POST['Disciplina'];
    $sql = "INSERT INTO umiestnenia (person_id,oh_id,placing, discipline)
            VALUES ('$osobaa','$olympiada','$umiestnenie','$disciplina')";
    $conn->exec($sql);
    echo "<p>Úspešne pridaný zaznam";
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
