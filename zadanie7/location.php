<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Poloha</title>
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
            <form action="location.php" method="post">
                <input type="submit" class="btn btn-primary" value="Suhlasim" name="accept">
            </form><br>
            <input type="button" class="btn btn-primary" onclick="nonaccept()" value="Nesuhlasim">
        </div>
    </div>
    <div class="nadpis">
        <h1 class="redd">Informácie o tvojej polohe</h1>
        <?php
        require_once "databaza/Database.php";
        $conn = (new Database())->Napojenie();
        $ip=$_SERVER['REMOTE_ADDR'];
        $sql ="SELECT * FROM ip_info where IP='$ip'";
        $result = mysqli_query($conn,$sql);

        echo "<p><span class='greenn'>Tvoja IP adresa je:</span> $ip</p>";
        $page="Poloha";
        $query=json_decode(file_get_contents('http://ip-api.com/json/'.$ip));
        function stdToArray($query){
            $reaged = (array)$query;
            foreach($reaged as $key => &$field){
                if(is_object($field))$field = stdToArray($field);
            }
            return $reaged;
        }
        $query=stdToArray($query);
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
                $country=$query['country'];
                $country_code=$query['countryCode'];
                echo"<p><span class='greenn'>Zemepisná šírka:</span> $lat</p>";
                echo"<p><span class='greenn'>Zemepisná dĺžka:</span> $lon</p>";

                if (isset($query['city'])&!(empty($query['city'])))
                    echo"<p><span class='greenn'>Mesto v ktorom sa nachádzaš:</span> {$query['city']}</p>";
                else
                    echo"<p><span class='greenn'>Mesto sa nedá lokalizovať alebo sa nachádzate na vidieku</span></p>";
                $query2=json_decode(file_get_contents('https://restcountries.eu/rest/v2/alpha/'.$country_code));
                $query2=stdToArray($query2);
                $capital=$query2['capital'];
                echo"<p><span class='greenn'>Štát v ktorom sa nachádzaš:</span> $country</p>";
                echo"<p><span class='greenn'>Hlavné mesto štátu:</span> $capital</p>";
            }
        }
        ?>
    </div>

</body>
</html>
