<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once "databaza/Database.php";
$conn = (new Database())->Napojenie();
$sql ="SELECT y1,y2,y3 FROM funkcie order by id desc limit 1";
$result = mysqli_query($conn,$sql);
$index=0;
$a=1;
$y1_bool=1;
$y2_bool=1;
$y3_bool=1;
if ($result->num_rows!=0){
    $hodnota = mysqli_fetch_assoc($result);
    $y1_bool=intval($hodnota['y1']);
    $y2_bool=intval($hodnota['y2']);
    $y3_bool=intval($hodnota['y3']);
}

while(true){
    $sql ="SELECT a FROM hodnota order by id desc limit 1";
    $result = mysqli_query($conn,$sql);
    if ($result->num_rows!=0){
        $hodnota = mysqli_fetch_assoc($result);
        $a=$hodnota['a'];
    }
    $arr=["x"=>$index,"a"=>$a];
    $y1 = sin ($index*$a)*sin ($index*$a);
    $y2 = cos ($index*$a)*cos ($index*$a);
    $y3 = sin ($index*$a)*cos ($index*$a);
    if ($y1_bool===1)
        $arr+=array("y1"=>$y1);
    if ($y2_bool===1)
        $arr+=array("y2"=>$y2);
    if ($y3_bool===1)
        $arr+=array("y3"=>$y3);
    $msg=json_encode($arr);
    posli_sse(++$index,$msg);
    sleep(1);
}
function posli_sse($id , $msg) {
    echo "id: $id\n";
    echo "event: message\n";
    echo "data: $msg\n\n";
    ob_flush();
    flush();
}