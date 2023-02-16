<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Curl</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles/styles.css">

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
<div id="modal_background2"></div>
<div class="modal_div2">
    <div id="modal_vrstva2">
        <h2>Načítavam dáta</h2>
        <hr>
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p>Po načítaní dát sa obnoví stránka</p>
    </div>
</div>
<div class="tlacidla">
    <button class="btn btn-primary" onclick="insertt();">Update databázy</button>
</div>
    <table class="tabulka" id="tabulka">
    <thead>
    <tr>
        <th>Meno
            <span onclick="zoradenie(0,false,0)" class="odkaz"><i class="bi bi-arrow-down"></i></span>
            <span onclick="zoradenie(0,true,0)" class="odkaz"><i class="bi bi-arrow-up"></i></span>
        </th>
        <?php
        require_once "triedy/databaza/Database.php";
        $conn = (new Database())->Napojenie();
        $sql ="SELECT id,timestamp_p FROM prednasky";
        $result = mysqli_query($conn,$sql);
        $i=0;
        while($prednasky = mysqli_fetch_assoc($result)) {
            $datum=date('j.M.Y', strtotime($prednasky['timestamp_p']));
            $pr_cislo=$prednasky['id'];
            $pr_cislo.=".";
            echo "<th>$pr_cislo<br>$datum</th>";
            $i=$i+1;
        }
        ?>
        <th>Počet účastí
            <span onclick="<?php $n=$i+1;echo"zoradenie($n,false,1)";?>" class="odkaz"><i class="bi bi-arrow-down"></i></span>
            <span onclick="<?php echo"zoradenie($n,true,1)";?>" class="odkaz"><i class="bi bi-arrow-up"></i></span>
        </th>
        <th>Počet minút
            <span onclick="<?php $n=$i+2;echo"zoradenie($n,false,1)";?>" class="odkaz"><i class="bi bi-arrow-down"></i></span>
            <span onclick="<?php echo"zoradenie($n,true,1)";?>" class="odkaz"><i class="bi bi-arrow-up"></i></span>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql2 ="SELECT distinct meno FROM ucast_studentov order by meno";
    $result2 = mysqli_query($conn,$sql2);
    $typ1="Joined";
    $typ2="Left";
    while($ucast = mysqli_fetch_assoc($result2)) {
        $odkazz="";
        $totalmin=0;
        $riadok=0;
        $meno=$ucast['meno'];
        echo" <tr>
            <td>$meno</td>";
        $sqlx ="SELECT distinct prednasky_id FROM ucast_studentov where meno='$meno'";
        $resultx = mysqli_query($conn,$sqlx);
        $ucast_pr=0;
        $idd=1;
        while($ucastx = mysqli_fetch_assoc($resultx)) {
            $noexit=false;
            $riadok+=1;
            $idd=$ucastx['prednasky_id'];

            for(;$riadok!=$idd;$riadok++)
               echo'<td>0</td>';

            $sql4 ="SELECT TIME(timestamp)
                FROM ucast_studentov where meno='$meno' and action='$typ2' and prednasky_id='$idd'";
            $result4 = mysqli_query($conn,$sql4);
            $sql5 ="SELECT TIME(timestamp)
                FROM ucast_studentov where meno='$meno' and action='$typ1' and prednasky_id='$idd'";
            $result5 = mysqli_query($conn,$sql5);
            $minuty=0;

            while($left = mysqli_fetch_assoc($result4)) {
            $time = $left['TIME(timestamp)'];
            $time = explode(':', $time);
            $minuty+=round(($time[0]*60) + ($time[1]) + ($time[2]/60));
            }
            while($joined = mysqli_fetch_assoc($result5)) {
                $time = $joined['TIME(timestamp)'];
                $time = explode(':', $time);
                $minuty-=round(($time[0]*60) + ($time[1]) + ($time[2]/60));
            }
            if ($result4->num_rows <$result5->num_rows){
                $noexit=true;
                $sqlm="SELECT max(timestamp) as najviac
                FROM ucast_studentov where action='$typ2' and prednasky_id='$idd'";
                $resultm=mysqli_query($conn,$sqlm);
                $rozdiel=$result5->num_rows-$result4->num_rows;
                $max = mysqli_fetch_assoc($resultm);
                for(;$rozdiel>0;$rozdiel--){
                    $time = date('G:i:s',strtotime($max['najviac']));
                    $time = explode(':', $time);
                    $minuty+=round(($time[0]*60) + ($time[1]) + ($time[2]/60));
                }
            }
            $sql3 ="SELECT COUNT(DISTINCT prednasky_id) as pocet
                FROM ucast_studentov where meno='$meno'";
            $result3 = mysqli_query($conn,$sql3);
            $ucast2 = mysqli_fetch_assoc($result3);
            $ucast_pr=$ucast2['pocet'];
            $odkazz="kliknutie(";
            $odkazz.="$idd,";
            $odkazz.="'$meno'";
            $odkazz.=")";
            if ($noexit)
                echo '<td><span class="noexitt odkaz" onclick="'.$odkazz.'">'.$minuty.'</span></td>';
            else
                echo'<td>
                    <span class="odkaz" onclick="'.$odkazz.'">'.$minuty.'</span>
                    </td>';
            $totalmin+=$minuty;
            }
        $sqlp ="SELECT count(*) as pocet FROM prednasky";
        $resultp = mysqli_query($conn,$sqlp);
        $maxc=mysqli_fetch_assoc($resultp);
        for(;$riadok<$maxc['pocet'];$riadok++)
            echo'<td>0</td>';

            echo"<td>$ucast_pr</td>";
            echo"<td>$totalmin</td>
                </tr>";
        $totalmin=0;
    }
    $conn->close();
    ?>

    </tbody>
</table>
<div id="modal_background"></div>
<div class="modal_div">
    <div id="modal_vrstva">

    </div>
</div>

</body>
</html>