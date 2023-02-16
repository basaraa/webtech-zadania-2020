<?php
require_once "databaza/Database.php";
$conn = (new Database())->Napojenie();
if (isset($_POST['load_xml'])) {

    $xml=simplexml_load_file("meniny.xml");
    $krajiny=[
                "SK"=>"Slovensko",
                "CZ"=>"Česko",
                "AT"=>"Rakúsko",
                "HU"=>"Maďarsko",
                "PL"=>"Poľsko",
    ] ;
    $den="";
    $mesiac="";
    $kod="";
    $nazov="";
    $dni_id=0;
    $krajiny_id=0;
    $typ="";
    $nazov_zaznamy="";
    $sql_dni=$conn->prepare("INSERT IGNORE INTO dni (day,month)
                    VALUES (?,?)") ;
    $sql_dni->bind_param('dd', $den,$mesiac);
    $sql_krajiny=$conn->prepare("INSERT IGNORE INTO krajiny (kod,nazov)
                    VALUES (?,?)") ;
    $sql_krajiny->bind_param('ss', $kod,$nazov);
    $sql_zaznamy=$conn->prepare("INSERT IGNORE INTO zaznamy (dni_id,krajiny_id,typ,nazov)
                    VALUES (?,?,?,?)") ;
    $sql_zaznamy->bind_param('ddss', $dni_id,$krajiny_id,$typ,$nazov_zaznamy);
    foreach($xml->children() as $riadok){
        $den=substr($riadok->den,2,2);
        $mesiac=substr($riadok->den,0,2);
        $sql_dni->execute();
        $sql_denID ="SELECT id FROM dni where day='$den' and month='$mesiac'";
        $result_den = mysqli_fetch_assoc(mysqli_query($conn,$sql_denID));
        $dni_id=$result_den['id'];
        foreach (array_keys((array) $riadok) as $hodnota){
            if (array_key_exists($hodnota,$krajiny)){
                $kod=$hodnota;
                $nazov=$krajiny[$hodnota];
                $sql_krajiny->execute();
                $typ="meno";
                $sql_krajinaID ="SELECT id FROM krajiny where kod='$kod'";
                $result_krajina = mysqli_fetch_assoc(mysqli_query($conn,$sql_krajinaID));
                $krajiny_id=$result_krajina['id'];
                foreach (explode(",",$riadok->$hodnota) as $name){
                    $nazov_zaznamy=trim($name);
                    if (!(empty($nazov_zaznamy))&&$nazov_zaznamy!="-")
                        $sql_zaznamy->execute();
                }
            }
        }
        if ($riadok->SKd){
            $typ="meno";
            $sql_krajinaID ="SELECT id FROM krajiny where kod='SK'";
            $result_krajina = mysqli_fetch_assoc(mysqli_query($conn,$sql_krajinaID));
            $krajiny_id=$result_krajina['id'];
            foreach (explode(",",$riadok->SKd) as $name){
                $nazov_zaznamy=trim($name);
                if (!(empty($nazov_zaznamy))&&$nazov_zaznamy!="-")
                    $sql_zaznamy->execute();
            }
        }
        if ($riadok->SKsviatky){
            $typ="sviatok";
            $sql_krajinaID ="SELECT id FROM krajiny where kod='SK'";
            $result_krajina = mysqli_query($conn,$sql_krajinaID);
            if ($result_krajina->num_rows===0){
                $kod="SK";
                $nazov="Slovensko";
                $sql_krajiny->execute();
                $sql_krajinaID ="SELECT id FROM krajiny where kod='SK'";
                $result_krajina = mysqli_query($conn,$sql_krajinaID);
            }
            $result_krajina=mysqli_fetch_assoc($result_krajina);
            $krajiny_id=$result_krajina['id'];
            $nazov_zaznamy=$riadok->SKsviatky;
            $sql_zaznamy->execute();
        }
        if ($riadok->SKdni){
            $typ="pamiatka";
            $sql_krajinaID ="SELECT id FROM krajiny where kod='SK'";
            $result_krajina = mysqli_fetch_assoc(mysqli_query($conn,$sql_krajinaID));
            $krajiny_id=$result_krajina['id'];
            $nazov_zaznamy=$riadok->SKdni;
            $sql_zaznamy->execute();
        }
        if ($riadok->CZsviatky){
            $typ="sviatok";
            $sql_krajinaID ="SELECT id FROM krajiny where kod='CZ'";
            $result_krajina = mysqli_query($conn,$sql_krajinaID);
            if ($result_krajina->num_rows===0){
                $kod="CZ";
                $nazov="Česko";
                $sql_krajiny->execute();
                $sql_krajinaID ="SELECT id FROM krajiny where kod='CZ'";
                $result_krajina = mysqli_query($conn,$sql_krajinaID);
            }
            $result_krajina=mysqli_fetch_assoc($result_krajina);
            $krajiny_id=$result_krajina['id'];
            $nazov_zaznamy=$riadok->CZsviatky;
            $sql_zaznamy->execute();
        }

    }
}?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Zadanie č.6 - Služby</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css">
    <script src="scripts/scripts.js"></script>
</head>
<body>
<header>
    <h1>Zadanie č.6 - Služby</h1>
    <hr>
</header>
<form action="index.php" method="post" enctype="multipart/form-data">
    <input class="btn btn-primary" type="submit" value="Načítaj xml" name="load_xml">
</form>
<button class="btn btn-primary" onclick="zobraz_vrstvu3(false)">Vyhľadať dátum v kalendári</button>
<button class="btn btn-primary" onclick="zobraz_vrstvu2(false)">Vyhľadať meno v kalendári</button>

<form action="response.php" method="get" enctype="multipart/form-data">
    <input type="hidden" name="typ" value="SKsviatky">
    <input class="btn btn-primary" type="submit" value="Zobraz Slovenské sviatky">
</form>

<form action="response.php" method="get" enctype="multipart/form-data">
    <input type="hidden" name="typ" value="CZsviatky">
    <input class="btn btn-primary" type="submit" value="Zobraz České sviatky">
</form>

<form action="response.php" method="get" enctype="multipart/form-data">
    <input type="hidden" name="typ" value="SKdni">
    <input class="btn btn-primary" type="submit" value="Zobraz Slovenské pamätné dni">
</form>

<button class="btn btn-primary" onclick="zobraz_vrstvu(false)">Pridať nové meno</button>
<div id="modal_background"></div>
<div class="modal_div">
    <div id="modal_vrstva">
        <div class="konec">
            <button class="btn btn-primary" onclick="zobraz_vrstvu(true)">Zavrieť</button>
        </div>
        <hr>
        <form action="response.php" method="post" enctype="multipart/form-data">
            <label for="meno" class="greenc">Zadaj meno:</label><p></p>
            <input class="form-control" type="text" name="meno" id="meno" required>
            <label for="datum" class="greenc">Zadaj dátum:</label><p></p>
            <input class="form-control" type="text" name="datum" id="datum" required>
            <input class="btn btn-primary buttonn" type="submit" value="Pridaj meno">
        </form>
    </div>
</div>
<div class="modal_div2">
    <div id="modal_vrstva2">
        <div class="konec">
            <button class="btn btn-primary" onclick="zobraz_vrstvu2(true)">Zavrieť</button>
        </div>
        <hr>
        <form action="response.php" method="get" enctype="multipart/form-data">
            <input type="hidden" name="typ" value="meno">
            <label for="meno1" class="greenc">Zadaj meno:</label><p></p>
            <input class="form-control" type="text" name="meno" id="meno1" required><br>
            <p class="greenc">Vyber krajinu z ktorej chceš hľadať:</p>
            <?php
            $sql_krajinaID ="SELECT kod,nazov FROM krajiny";
            $result_krajina = mysqli_query($conn,$sql_krajinaID);
            while($krajina = mysqli_fetch_assoc($result_krajina)) {
                $code=$krajina['kod'];
                $meno=$krajina['nazov'];
                echo "<input type='radio' value='$code' id='$code' name='kod' required>
                    <label for='$code'>$meno</label>";
            }
            ?><br>
            <input class="btn btn-primary buttonn" type="submit" value="Zobraz kedy má dané meno meniny">
        </form>
    </div>
</div>
<div class="modal_div3">
    <div id="modal_vrstva3">
        <div class="konec">
            <button class="btn btn-primary" onclick="zobraz_vrstvu3(true)">Zavrieť</button>
        </div>
        <hr>
        <form action="response.php" method="get" enctype="multipart/form-data">
            <input class="form-control" type="hidden" name="typ" value="datum">
            <label for="datum1" class="greenc">Zadaj datum:</label><p></p>
            <input class="form-control" type="text" name="datum" id="datum1" required><br>
            <p class="greenc">Vyber krajinu z ktorej chceš hľadať:</p>
            <?php
            $sql_krajinaID ="SELECT kod,nazov FROM krajiny";
            $result_krajina = mysqli_query($conn,$sql_krajinaID);
            while($krajina = mysqli_fetch_assoc($result_krajina)) {
                $code=$krajina['kod'];
                $meno=$krajina['nazov'];
                echo "<input type='radio' value='$code' id='$code.1' name='kod'>
                        <label for='$code.1'>$meno</label>";
            }
            mysqli_close($conn);
            ?><br>
            <input class="btn btn-primary buttonn" type="submit" value="Zobraz kto má v daný dátum meniny">
        </form>
    </div>
</div>
<?php
include_once ("rest_doc.php");
?>

</body>
</html>
