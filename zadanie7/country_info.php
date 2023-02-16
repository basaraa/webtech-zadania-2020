<?php
if (isset($_POST["country"])&&isset($_POST["code"])){
    $country_code=$_POST["code"];
    $country=$_POST["country"];
    echo'<div class="konec">
            <button class="btn btn-primary" onclick="zobraz_vrstvu()">Zavrieť</button>
        </div>';
    echo"<h2 class='nadpis'>Krajina: <span class='menoo'>$country</span></h2> <hr>";
    echo'<table class="tabulka" id="tabulka">
                <thead>
                <tr>
                <th>Mesto</th>
                <th>Počet návštev</th>
                </tr>
                </thead>
                ';
    require_once "databaza/Database.php";
    $conn = (new Database())->Napojenie();
    $sql ="SELECT city,count(ip_info.id) as pocet FROM location_info join ip_info ON ip_info.id=location_info.IP_id where country='$country' and country_code='$country_code' group by city";
    $result = mysqli_query($conn,$sql);
    $i=0;
    while($krajina = mysqli_fetch_assoc($result)) {
        $kraj=$krajina['city'];
        $pocet=$krajina['pocet'];

        if (empty($kraj))
            echo"<tr>
            <td>Nelokalizované mestá a vidiek</td>
            <td>$pocet</td>
        </tr>";
        else
            echo"<tr>
            <td>$kraj</td>
            <td>$pocet</td>
        </tr>";

    }
    echo"<tbody>
                </tbody>
                </table>";
}
?>
