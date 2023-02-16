<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Počasie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
            <form action="index.php" method="post">
                <input type="submit" class="btn btn-primary" value="Suhlasim" name="accept">
            </form><br>
            <input type="button" class="btn btn-primary" onclick="nonaccept()" value="Nesuhlasim">
        </div>
    </div>
    <div class="nadpis">
        <h1 class="redd">Predpoveď počasia pre tvoju polohu</h1>
        <?php
        require_once "databaza/Database.php";
        $conn = (new Database())->Napojenie();
        $API_key= "9229c3bbc9fee7e807af4206be27aec8";
        $ip=$_SERVER['REMOTE_ADDR'];
        $query=json_decode(file_get_contents('http://ip-api.com/json/'.$ip));
        $sql ="SELECT * FROM ip_info where IP='$ip'";
        $result = mysqli_query($conn,$sql);

        function stdToArray($query){
            $reaged = (array)$query;
            foreach($reaged as $key => &$field){
                if(is_object($field))$field = stdToArray($field);
            }
            return $reaged;
        }
        $query=stdToArray($query);
        $page="Domov";
        if ($result->num_rows===0&&(!(isset($_POST['accept'])))){
            echo '<script>
            document.getElementById("modal_background").style.display="block";
            document.getElementsByClassName("modal_div")[0].style.display="flex";
            </script>';
        }
        else{
            include_once ('insert.php');
            if (isset($query['status'])&$query['status']==="success"){
                $lat=$query['lat'];
                $lon=$query['lon'];
                $code=$query['countryCode'];
                $query2=json_decode(file_get_contents('http://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&units=metric&lon='.$lon.'&lang=SK&appid='.$API_key));
                $query2=stdToArray($query2);
                $query2['weather']=stdToArray($query2['weather']);
                $icon=$query2['weather'][0]['icon'];
                echo"<div class='weather-icon'><img src='icons/$icon.png' alt='icon'/></div>";
                echo"<p>{$query2['weather'][0]['description']}</p>";
                echo"<p><span class='greenn'>Priemerná teplota:</span> {$query2['main']['temp']} °C</p>";
                echo"<p><span class='greenn'>Pocitová teplota:</span> {$query2['main']['feels_like']} °C</p>";
                echo"<p><span class='greenn'>Minimálna teplota:</span> {$query2['main']['temp_min']} °C</p>";
                echo"<p><span class='greenn'>Maximálna teplota:</span> {$query2['main']['temp_max']} °C</p>";
                echo"<p><span class='greenn'>Vlhkosť:</span> {$query2['main']['humidity']}%</p>";
                echo"<p><span class='greenn'>Rýchlosť vetra:</span> {$query2['wind']['speed']} km/h</p>";
            }
        }
        ?>
    </div>
<div id="xx"></div>
</body>
</html>
