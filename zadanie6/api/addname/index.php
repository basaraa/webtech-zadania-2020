<?php
if (!(isset($conn))){
    require_once "../../databaza/Database.php";
    $conn = (new Database())->Napojenie();
}
if (isset($_POST['meno'])&&isset($_POST["datum"])){
    $meno=$_POST["meno"];
    $datum=$_POST["datum"];
    $datum=explode(".",$datum);
    $den=$datum[0];
    $mesiac=$datum[1];
    $dni_id=0;
    $krajiny_id=0;
    $sql_denID ="SELECT id FROM dni where day='$den' and month='$mesiac'";
    $result_den = mysqli_fetch_assoc(mysqli_query($conn,$sql_denID));
    if (mysqli_query($conn, $sql_denID)->num_rows!=0)
        $dni_id=$result_den['id'];
    $sql_krajinaID ="SELECT id FROM krajiny where kod='SK'";
    $result_krajina = mysqli_fetch_assoc(mysqli_query($conn,$sql_krajinaID));
    if (mysqli_query($conn, $sql_krajinaID)->num_rows!=0)
        $krajiny_id=$result_krajina['id'];
    if (mysqli_query($conn, $sql_denID)->num_rows===0){
        $msg = "Zlyhanie pridania nového mena.";
        $status = "failed";
    }
    else if (mysqli_query($conn, $sql_krajinaID)->num_rows===0){
        $msg = "Zlyhanie pridania nového mena.";
        $status = "failed";
    }
    else{
        $sql="INSERT INTO zaznamy SET dni_id='$dni_id', krajiny_id='$krajiny_id', typ='meno', nazov='$meno' ";
        if( mysqli_query($conn, $sql)) {
            $msg = "Úspešne pridané meno.";
            $status = "success";
        } else {
            $msg = "Zlyhanie pridania nového mena.";
            $status = "failed";
        }
    }
    $empResponse = array(
        'status' => $status,
        'status_message' => $msg
    );

    header('Content-Type: application/json');
    echo json_encode($empResponse, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}
else{
    $empResponse =array(
        'status' => 'failed',
        'status_message' => 'Nebol zadaný nejaký parameter.'
    );
    echo json_encode($empResponse, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}