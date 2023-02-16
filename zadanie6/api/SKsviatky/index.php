<?php
if (!(isset($conn))){
    require_once "../../databaza/Database.php";
    $conn = (new Database())->Napojenie();
}
header('Content-Type: application/json');
$sql = "SELECT zaznamy.nazov as Sviatok,krajiny.nazov as Country,CONCAT(dni.day,'.',dni.month,'.') as Datum FROM zaznamy 
                join dni ON dni.id=zaznamy.dni_id 
                join krajiny ON krajiny.id=zaznamy.krajiny_id
                where zaznamy.typ='sviatok' and krajiny.kod='SK' order by dni.id";
$result = mysqli_query($conn, $sql);
$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
