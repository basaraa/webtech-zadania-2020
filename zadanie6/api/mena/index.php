<?php
if (!(isset($conn))){
    require_once "../../databaza/Database.php";
    $conn = (new Database())->Napojenie();
}
if (isset($_GET['meno'])&&isset($_GET['kod'])){
    $meno=$_GET['meno'];
    $kod=$_GET['kod'];
    header('Content-Type: application/json');
    $sql ="SELECT zaznamy.nazov as Meno,krajiny.nazov as Country,CONCAT(dni.day,'.',dni.month,'.') as Datum FROM zaznamy 
                join dni ON dni.id=zaznamy.dni_id 
                join krajiny ON krajiny.id=zaznamy.krajiny_id
                where zaznamy.typ='meno' and krajiny.kod='$kod' and zaznamy.nazov='$meno' order by dni.id";
    $result = mysqli_query($conn,$sql);
    if($result->num_rows===0) {
        $result = array(
            'status' => "failed",
            'status_message' => "Nenájdený žiaden záznam so zadaným menom"
        );
    }
    else
        $result=mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}