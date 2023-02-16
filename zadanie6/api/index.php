<?php
$method=$_SERVER['REQUEST_METHOD'];
if (isset($method)){
    if (!(isset($conn))){
        require_once "../databaza/Database.php";
        $conn = (new Database())->Napojenie();
    }
    if($method==='GET'&&isset($_GET['typ'])){
        $typ=$_GET['typ'];
        if ($typ==="SKsviatky")
            include("SKsviatky/index.php");
        if ($typ==="CZsviatky")
            include("CZsviatky/index.php");
        if ($typ==="SKdni")
            include("SKdni/index.php");
        if ($typ==="datum")
            include ("datumy/index.php");
        if ($typ==="meno")
            include ("mena/index.php");

    }
    else if ($method==='POST'&&isset($_POST['meno'])){
        include ("addname/index.php");
    }
    else{
        header('Content-Type: application/json');
        $result = array(
            'status' => "failed",
            'status_message' => "Neboli zadané správne parametre"
        );
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

    mysqli_close($conn);
}
?>
