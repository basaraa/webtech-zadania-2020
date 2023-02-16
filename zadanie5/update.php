<?php
require_once "databaza/Database.php";
$conn = (new Database())->Napojenie();
if (isset($_POST['hodnota'])){
    $a=$_POST['hodnota'];
    $last = "SELECT id FROM hodnota order by id desc limit 1";
    $result = mysqli_query($conn, $last);
    if ($result->num_rows===0){
        $stmt =$conn->prepare("INSERT INTO hodnota (a)
                VALUES (?)") ;
        $stmt->bind_param('d', $a);
        $stmt->execute();
    }
    else{
    $row = mysqli_fetch_assoc($result);
    $last_id = $row['id'];
    $sql = "UPDATE hodnota SET a='$a' where id='$last_id'";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    }
}