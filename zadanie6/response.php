<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Zadanie č.6 - Služby</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<header>
    <h1>Zadanie č.6 - Služby</h1>
    <hr>
</header>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $param="https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/";
    $param.="?typ=".$_GET['typ'];
    if (isset($_GET['meno']))
        $param.="&meno=".$_GET['meno'];
    if (isset($_GET['datum']))
        $param.="&datum=".$_GET['datum'];
    if (isset($_GET['kod']))
        $param.="&kod=".$_GET['kod'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    require_once "databaza/Database.php";
    $conn = (new Database())->Napojenie();
    $response = curl_exec($ch);
    $response=json_decode($response,true);
    if (!(is_array($response)))
        $response=array($response);
    if (isset($response['status']))
        echo"Odpoveď:<br>Status: {$response['status']}<br>Správa: {$response['status_message']}";
    foreach($response as $msg){
        if (isset($msg['Sviatok']))
            echo"Sviatok: {$msg['Sviatok']}<br>";
        if (isset($msg['Nazov']))
            echo"Nazov: {$msg['Nazov']}<br>";
        if (isset($msg['Meno']))
            echo"Meno: {$msg['Meno']}<br>";
        if (isset($msg['Country']))
            echo"Krajina: {$msg['Country']}<br>";
        if (isset($msg['Datum']))
            echo"Datum: {$msg['Datum']}<br><br>";
    }
curl_close($ch);
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $param="";
    if (isset($_POST['meno']))
        $param.="meno=".$_POST['meno'];
    if (isset($_POST['datum']))
        $param.="&datum=".$_POST['datum'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://wt116.fei.stuba.sk/WebTt2zadania/zadanie6/api/");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        $param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    require_once "databaza/Database.php";
    $conn = (new Database())->Napojenie();
    $response = curl_exec($ch);
    $response=json_decode($response,true);
    echo"Odpoveď:<br>Status: {$response['status']}<br>Správa: {$response['status_message']}";
    curl_close($ch);
}
?><br>
<button class="btn btn-primary" onclick="location.href='index.php'">Späť na úvodnú stránku</button>

</body>
</html>
