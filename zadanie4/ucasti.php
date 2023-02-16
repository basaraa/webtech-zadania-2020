<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Curl</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="scripts/scripts.js"></script>
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="index.php">Domov</a></li>
            <li><a href="ucasti.php">Štatistika účasti</a></li>
        </ul>
    </nav>
    <hr>
</header>
<?php
require_once "triedy/databaza/Database.php";
$conn = (new Database())->Napojenie();
$sql ="select prednasky_id,count(distinct meno) as pocet  from (ucast_studentov
                                join prednasky ON prednasky.id=ucast_studentov.prednasky_id)
        group by prednasky_id order by prednasky_id asc";
$result = mysqli_query($conn,$sql);
$dataPoints = array();
while($prednasky = mysqli_fetch_assoc($result)) {
    $id="Prednáška č.";
    $id.=$prednasky['prednasky_id'];
    $pocet=$prednasky['pocet'];
    array_push($dataPoints, array("label"=> $id, "y"=> $pocet));
}
?>
<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title:{
                text: "Štatistika účasti na prednáškach"
            },
            data: [{
                indexLabel: "{label}: ({y})",
                type: "pie",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();
    }
</script>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
</body>
</html>
