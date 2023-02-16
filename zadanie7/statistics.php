<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Štatistika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="scripts/scripts.js"></script>
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="index.php">Domov</a></li>
            <li><a href="location.php">Poloha</a></li>
            <li><a href="statistics.php">Štatistika</a></li>
        </ul>
    </nav>
    <hr>
</header>
<div id="modal_background"></div>
<div class="modal_div">
    <div id="modal_vrstva">
        <h2>Táto stránka používa informácie:</h2>
        <ul>
            <li>O vašej IP adrese</li>
            <li>O vašej polohe</li>
        </ul>
        <p>Svojím súhlasom povoľujete používanie týchto informácii, ak súhlas neudelíte nemôžete pokračovať na stránku.</p>
        <form action="statistics.php" method="post">
            <input type="submit" class="btn btn-primary" value="Suhlasim" name="accept">
        </form><br>
        <input type="button" class="btn btn-primary" onclick="nonaccept()" value="Nesuhlasim">
    </div>
</div>
<div id="modal_background2"></div>
<div class="modal_div2">
    <div id="modal_vrstva2">

    </div>
</div>
<div class="nadpis">
    <h1 class="purpple">Štatistika návštevníkov z jednotlivých krajín</h1>

            <?php
            $ip=$_SERVER['REMOTE_ADDR'];
            $page="Štatistika";
            $query=json_decode(file_get_contents('http://ip-api.com/json/'.$ip));
            function stdToArray($query){
                $reaged = (array)$query;
                foreach($reaged as $key => &$field){
                    if(is_object($field))$field = stdToArray($field);
                }
                return $reaged;
            }
            $query=stdToArray($query);
            require_once "databaza/Database.php";
            $conn = (new Database())->Napojenie();
            $sql ="SELECT * FROM ip_info where IP='$ip'";
            $result = mysqli_query($conn,$sql);
            if ($result->num_rows===0&&(!(isset($_POST['accept'])))){
                echo '<script>
            document.getElementById("modal_background").style.display="block";
            document.getElementsByClassName("modal_div")[0].style.display="flex";
            </script>';
            }
            else{
                include_once ('insert.php');
                $sql2 ="SELECT country,country_code,count(ip_info.id) as pocet FROM location_info join ip_info ON ip_info.id=location_info.IP_id group by country,country_code";
                $result2 = mysqli_query($conn,$sql2);
                echo'<table class="tabulka" id="tabulka"><thead>
                    <tr>
                        <th>Vlajka</th>
                        <th>Názov</th>
                        <th>Počet návštev</th>
                    </tr>
                </thead><tbody>';
                while($location = mysqli_fetch_assoc($result2)) {
                    $pocet=$location['pocet'];
                    $code=$location['country_code'];
                    $country=$location['country'];
                    $odkazz="kliknutie('$code','$country')";
                    $code=strtolower($code);
                    $flag = "https://www.geonames.org/flags/x/".$code.".gif";
                    echo "<tr>
                          <td><img src='$flag' alt='flag' class='img'></td>";
                    echo '<td><span class="odkaz" onclick="'.$odkazz.'">'.$country.'</span></td>';
                    echo "<td>$pocet</td>";
                    echo "</tr>";
                }
                echo'</tbody></table>';
                echo'<h1 class="purpple">Mapa pozícií návštevníkov</h1>';
                echo '<div id="mapid"></div>';

                $ip_id=$result['id'];
                include_once ('info.php');
                $sqlxx ="SELECT lat,lon from location_info";
                $resultxx = $conn->query($sqlxx);
                $resultxx=$resultxx->fetch_all(MYSQLI_ASSOC);
            }
            ?>
</div>
<script>
    let mymap = L.map('mapid').setView([48.625,21.7269], 1);

    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution:'&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);

    let data = <?php echo JSON_encode($resultxx); ?>;
    for (let i = 0; i < data.length; i++) {
        L.marker([parseFloat(data[i].lat), parseFloat(data[i].lon)]).addTo(mymap).bindPopup('lat: '+parseFloat(data[i].lat)+'<br>lon: '+parseFloat(data[i].lon))
    }

</script>


</body>
</html>
