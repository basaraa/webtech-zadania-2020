<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Oznam o uploadu</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico"/>
    <link rel="stylesheet" href="styles/Styles.css">
</head>
<body>

<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="index.php">Domov</a></li>
            <li><a href="upload_file.php">Upload</a></li>
        </ul>
    </nav>
    <hr>
</header>
<?php
$priecinok="/home/xpac/public_html/files/";
$target_file =  $priecinok.basename($_FILES["Upload_suboru"]["name"]);
$kontrola_chyb = 0;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$target_file=$priecinok.$_POST['NazovSuboru'].".".$imageFileType;
if (file_exists($target_file))
    $target_file=$priecinok.$_POST['NazovSuboru']."_".time().".".$imageFileType;
if ($_FILES["Upload_suboru"]["size"] > (2*1024*1024)) {
    echo "Súbor je príliš veľký";
    $kontrola_chyb = 1;
}
if ($kontrola_chyb == 1) {
    echo "Niekde nastala chyba a súbor sa nenahral";
} else {
    if (move_uploaded_file($_FILES["Upload_suboru"]["tmp_name"], $target_file)) {
        echo "Súbor ". htmlspecialchars($target_file). " bol uploadnutý";
    } else {
        echo "Zlyhanie uplodovania súboru";
    }
}?>
</body>
</html>
