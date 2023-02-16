<?php
if (!(isset($conn))){
    require_once "../../databaza/Database.php";
    $conn = (new Database())->Napojenie();
}
if (isset($_GET['datum'])&&isset($_GET['kod'])){
    $datum=$_GET['datum'];
    $datum=explode(".",$datum);
    $den=$datum[0];
    $mesiac=$datum[1];
    $kod=$_GET['kod'];
    header('Content-Type: application/json');
    $sql ="SELECT zaznamy.nazov as Meno,krajiny.nazov as Country,CONCAT(dni.day,'.',dni.month,'.') as Datum FROM zaznamy 
                join dni ON dni.id=zaznamy.dni_id 
                join krajiny ON krajiny.id=zaznamy.krajiny_id
                where zaznamy.typ='meno' and krajiny.kod='$kod' and dni.day='$den' and dni.month='$mesiac' order by dni.id";
    $result = mysqli_query($conn,$sql);
    if($result->num_rows===0) {
        $result = array(
            'status' => "failed",
            'status_message' => "Nenájdený žiaden záznam so zadaným dátumom"
        );
    }
    else
        $result=mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}