<?php
require_once "databaza/Database.php";
$conn = (new Database())->Napojenie();
if (isset($_POST['y1'])){
    require_once "databaza/Database.php";
    $conn = (new Database())->Napojenie();
    $y1= $_POST['y1'];
    $y2=$_POST['y2'];
    $y3=$_POST['y3'];
    $last = "SELECT id FROM funkcie order by id desc limit 1";
    $result = mysqli_query($conn, $last);
    if ($result->num_rows===0){
        $stmt =$conn->prepare("INSERT INTO funkcie (y1,y2,y3)
                VALUES (?,?,?)") ;
        $stmt->bind_param('ddd', $y1,$y2,$y3);
        $stmt->execute();
    }
    else{
    $row = mysqli_fetch_assoc($result);
    $last_id = $row['id'];
    $sql = "UPDATE funkcie SET y1='$y1',y2='$y2',y3='$y3' where id='$last_id'";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Zadanie č.5 - SSE</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3"></script>
    <link rel="stylesheet" href="styles/Styles.css">
    <script src="scripts/scripts.js"></script>
</head>
<body>
<header><h1>Zadanie č.5 - alternatíva č.1</h1></header>
<hr>
<canvas id="graf"></canvas>
<div id="vypis"></div>
    <div id="valuess">
        <label id="hod_lab" for="hodnota">Zadaj hodnotu:</label><br>
        <input class="form-check-input" name="hodnota" value="1" id="hodnota" type="number">
        <div class="tlacidla">
            <input class="btn btn-primary" type="submit" value="Potvrdiť hodnotu" onclick="kliknutie()">
        </div>
    </div>
<form action="index.php" method="post">
    <p>Chceš posielať funkciu y1=sin<sup>2</sup>(ax)?</p>
    <label class="form-check-label" for="y1_1">ano</label>
    <input class="form-check-input" type="radio" name="y1" id="y1_1" value="1" required>
    <label class="form-check-label" for="y1_2">nie</label>
    <input class="form-check-input" type="radio" name="y1" id="y1_2" value="0">
    <p>Chceš posielať funkciu y2=cos<sup>2</sup>(ax)?</p>
    <label class="form-check-label" for="y2_1">ano</label>
    <input class="form-check-input" type="radio" name="y2" id="y2_1" value="1" required>
    <label class="form-check-label" for="y2_2">nie</label>
    <input class="form-check-input" type="radio" name="y2" id="y2_2" value="0">
    <p>Chceš posielať funkciu y3=sin(ax)*cos(ax)?</p>
    <label class="form-check-label" for="y3_1">ano</label>
    <input class="form-check-input" type="radio" name="y3" id="y3_1" value="1" required>
    <label class="form-check-label" for="y3_2">nie</label>
    <input class="form-check-input" type="radio" name="y3" id="y3_2" value="0">
    <div class="tlacidla">
        <input class="btn btn-primary" type="submit" value="Potvrdiť">
    </div>
</form>
</body>
</html>