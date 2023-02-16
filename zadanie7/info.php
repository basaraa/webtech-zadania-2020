<?php

if (!(isset($conn))){
    require_once "databaza/Database.php";
    $conn = (new Database())->Napojenie();
}


echo"<h1 class='purpple'>Štatistika návštevníkov podľa ich lokálneho času</h1>";
$sqlx ="SELECT counter FROM time_count order by typ asc";
$resultx = mysqli_query($conn,$sqlx);
echo'<table class="tabulka" id="tabulka">
                <thead>
                <tr>
                    <th>6:00-15:00</th>
                    <th>15:00-21:00</th>
                    <th>21:00-24:00</th>
                    <th>24:00-6:00</th>
                </tr>
                </thead>
                <tbody>';
echo"<tr>";
while($visits = mysqli_fetch_assoc($resultx)) {
    $page_Count=$visits['counter'];
    echo "<td>$page_Count</td>";
}
echo"</tr>";
echo'</tbody></table>';
echo"<h1 class='purpple'>Štatistika návštev jednotlivých stránok</h1>";
$sqlx ="SELECT page_visit,count(page_visit) as pocet FROM visits GROUP BY page_visit order by pocet desc";
$resultx = mysqli_query($conn,$sqlx);
echo'<table class="tabulka" id="tabulka">
                <thead>
                <tr>
                    <th>Stránka</th>
                    <th>Počet návštev</th>
                </tr>
                </thead>
                <tbody>';
while($visits = mysqli_fetch_assoc($resultx)) {
    $page_name=$visits['page_visit'];
    $page_Count=$visits['pocet'];
    echo"<tr>";
    echo "<td>$page_name</td>";
    echo "<td>$page_Count</td>";
    echo"</tr>";
}
echo'</tbody></table>';




?>


