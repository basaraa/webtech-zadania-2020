<?php
if (isset($_POST["idcko"])&&isset($_POST["meno"])){
    $idcko=$_POST["idcko"];
    $meno=$_POST["meno"];
    echo'<div class="konec">
            <button class="btn btn-primary" onclick="zobraz_vrstvu()">Zavrieť</button>
        </div>';
    echo"<h2 class='nadpis'>Meno Študenta: <span class='menoo'>$meno</span></h2> <hr>";
    echo'<table class="tabulka" id="tabulka">
                <thead>
                <tr>
                <th>Akcia</th>
                <th>Čas</th>
                </tr>
                </thead>
                ';
    require_once "triedy/databaza/Database.php";
    $conn = (new Database())->Napojenie();
    $sql ="SELECT meno,action,time(timestamp) FROM ucast_studentov where meno='$meno' and prednasky_id='$idcko'";
    $result = mysqli_query($conn,$sql);
    $i=0;
    while($prednasky = mysqli_fetch_assoc($result)) {

        $akcia=$prednasky['action'];
        $cas=$prednasky['time(timestamp)'];
        echo"<tr>
            <td>$akcia</td>
            <td>$cas</td>
        </tr>";


    }
    echo"<tbody>
                </tbody>
                </table>";
}
?>

