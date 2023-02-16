<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Obsah priečinka</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico"/>
    <link rel="stylesheet" href="styles/Styles.css">
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="index.php">Domov</a></li>
            <li><a href="upload_file.php">Upload</a></li>
        </ul>
    </nav>
    <hr>
</header>
<h2>Výpis obsahu priečinka spolu s detailami:</h2>

<table class="tabulka" id="tabulka">
    <thead>
        <tr>
            <th>Názov súboru
                <span onclick="zoradenie(0,false,0)"><img src="img/arrowdown.png" alt="zoradenieasc"></span>
                <span onclick="zoradenie(0,true,0)"><img src="img/arrowup.png" alt="zoradeniedesc"></span>
            </th>
            <th>Veľkosť súboru
                <span onclick="zoradenie(1,false,1)"><img src="img/arrowdown.png" alt="zoradenieasc"></span>
                <span onclick="zoradenie(1,true,1)"><img src="img/arrowup.png" alt="zoradeniedesc"></span>
            </th>
            <th>Dátum uploadu
                <span onclick="zoradenie(2,false,2)"><img src="img/arrowdown.png" alt="zoradenieasc"></span>
                <span onclick="zoradenie(2,true,2)"><img src="img/arrowup.png" alt="zoradeniedesc"></span>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php
        if (isset($_GET["cesta"]))
            $cestaa=$_GET["cesta"]."/";
        else
            $cestaa="/home/xpac/public_html/files/";
        $overenie="/home/xpac/public_html/files";
        $current_priecinok=realpath($cestaa);
        if (strcmp($current_priecinok, $overenie)!==0)
            $priecinok= array_diff(scandir($cestaa), array('.'));
        else{
            $cestaa="/home/xpac/public_html/files/";
            $priecinok= array_diff(scandir($cestaa), array('.', '..'));
        }
        foreach($priecinok as $subor_priecinku) {
            if(is_dir($cestaa.$subor_priecinku)) {
                echo "<tr>\n";
                echo "<td><a href='"."?cesta=".$cestaa.$subor_priecinku."'>",
                basename($cestaa.$subor_priecinku),"</a></td>\n";
                echo "<td>","", "</td>\n";
                echo "<td>","","</td>\n";
                echo "</tr>\n";
            }
            else{
                echo "<tr>\n";
                echo "<td>",basename($cestaa.$subor_priecinku),"</td>\n";
                echo "<td>",filesize($cestaa.$subor_priecinku)," bajtov", "</td>\n";
                echo "<td>",date("d F Y, H:i:s",filemtime($cestaa.$subor_priecinku)),"</td>\n";
                echo "</tr>\n";
            }
        }
    ?>
    </tbody>
</table>
<script src="javascript/scripts.js"></script>
</body>
</html>