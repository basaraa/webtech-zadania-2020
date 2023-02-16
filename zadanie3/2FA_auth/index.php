<!DOCTYPE html>
<html lang="sk">

<head>
    <title>Login cez 2fa</title>
    <link rel="shortcut icon" type="image/jpg" href="../img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="../index.php">Domov</a></li>
            <li><a href="registracia_form.php">Registrácia</a></li>
        </ul>
    </nav>
    <hr>
</header>
<script src="../scripts/Scripts.js"></script>
<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Refresh:0");
}
if (!(isset($_SESSION['id']))){
    if(!(isset($_POST['Nickname']))){
        echo'<form action="index.php" method="post">
            <label for="Nickname">Zadaj login(email):</label><br>
            <input class="form-control" type="text" name="Nickname" id="Nickname" required><br>
            <label for="Heslo">Zadaj heslo:</label><br>
            <input class="form-control" type="password" name="Heslo" id="Heslo" required><br>
            <label for="verify_code">Zadaj verifikačný kód z aplikácie:</label><br>
            <input class="form-control" type="text" name="verify_code" id="verify_code" required><br>
            <div class="tlacidla">
                <input class="btn btn-primary" type="submit" value="Prihlásiť sa" id="submit">
            </div>
        </form>';
    }
    else{
        $login=$_POST['Nickname'];
        $heslo=md5($_POST['Heslo']);
        require_once "../triedy/reg_class.php";
        require_once "../triedy/databaza/Database.php";
        try {
            $conn = (new Database())->Napojenie();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("select * from users 
                        where email='$login' and heslo='$heslo' 
                        and typ='normal'");
            $stmt->execute();
            $resultt = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
            if ($resultt){
                $secret_code=$resultt[0]->getFaCode();
                require_once 'PHPGangsta/GoogleAuthenticator.php';
                $verify=$_POST['verify_code'];
                $ga = new PHPGangsta_GoogleAuthenticator();
                $result = $ga->verifyCode($secret_code, $verify);
                if ($result===true){
                    echo"<h1 class='nonerror'> Vitaj na stránke {$resultt[0]->getMeno()} {$resultt[0]->getPriezvisko()} </h1>";
                    echo'<div class="tlacidla">
                    <button class="btn btn-primary" onclick="location.href=\'../index.php\'" type="button">
                        Návrat späť na úvodnú stránku
                    </button>
                    <form action="index.php" method="post" enctype="multipart/form-data">
                            <input class="btn btn-primary" type="submit" value="logout" name="logout">
                    </form>
                    </div>';
                    echo"<h2>Osobné údaje:</h2>";
                    echo"Meno: {$resultt[0]->getMeno()}<br>
                         Priezvisko: {$resultt[0]->getPriezvisko()}<br>
                         Email: {$resultt[0]->getEmail()}<br>
                         Typ účtu: {$resultt[0]->getTyp()}<br>";
                    $_SESSION['id']=$resultt[0]->getId();

                    $time = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO pripojenia (users_id,time)
                        VALUES ('{$resultt[0]->getId()}','$time')";
                    $conn->exec($sql);
                    $idcko=$resultt[0]->getId();
                    echo'<div class="tlacidla">
                    <button onclick="show_div()" class="btn btn-primary">Zobraziť históriu prihlásení</button>
                </div>';
                    $stmt = $conn->prepare("select time  from pripojenia 
                                where users_id='$idcko'");
                    $stmt->execute();
                    $result1 = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
                    echo'<div id="historia"> 
                    <h2 class="nonerror">História prihlásení na tvojom účte:</h2>
                    <table class="tabulka" id="tabulka">
                    <thead>
                        <tr>
                            <th>Čas prihlásenia</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($result1 as $hist){
                        echo "<tr>
                    <td>{$hist->getTime()}</td>
                    </tr>";
                    }
                    echo'</tbody></table>';
                    echo'<h2 class="nonerror">Štatistika jednotlivých typov prihlásení </h2>';
                    $stmt = $conn->prepare("select users.typ,count(*) as pocet  from (pripojenia
                                join users ON users.id=pripojenia.users_id ) group by users.typ order by users.typ asc
                                ");
                    $stmt->execute();
                    $result1 = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
                    echo'<table class="tabulka" id="tabulka2">
                    <thead>
                        <tr>
                            <th>Klasické prihlásenie</th>
                            <th>Ldap</th>
                            <th>Google</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($result1 as $r){
                        if ($r->getTyp()==="normal")
                            $x[0]=$r->getPocet();
                        if ($r->getTyp()==="ldap")
                            $x[1]=$r->getPocet();
                        if ($r->getTyp()==="google")
                            $x[2]=$r->getPocet();
                    }
                    if (!(isset($x[0])))
                        $x[0]=0;
                    if (!(isset($x[1])))
                        $x[1]=0;
                    if (!(isset($x[2])))
                        $x[2]=0;

                    echo "<tr>
                    <td>{$x[0]}</td>
                    <td>{$x[1]}</td>                   
                    <td>{$x[2]}</td>                                      
                  </tr>";
                    echo'</tbody></table></div>';
                }
                else {
                    echo"<h1 class='error'> Zlyhanie prihlásenia: Chybný overovací kód";
                    echo'<div class="tlacidla">
                    <button class="btn btn-primary" onclick="location.href=\'index.php\'" type="button">
                        Späť na prihlásenie
                    </button>
                    </div>';
                }
            }
            else{
                echo"<h1 class='error'> Zlyhanie prihlásenia: Zlý email alebo heslo";
                echo'<div class="tlacidla">
                    <button class="btn btn-primary" onclick="location.href=\'index.php\'" type="button">
                        Späť na prihlásenie
                    </button>
                    </div>';
            }
        } catch(PDOException $e) {
            echo "<p>Error: " . $e->getMessage();
        }
        $conn = null;

        }
    }
else{
    require_once "../triedy/reg_class.php";
    require_once "../triedy/databaza/Database.php";
    $idcko=$_SESSION['id'];
    try {
        $conn = (new Database())->Napojenie();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("select * from users 
                                where id='$idcko'");
        $stmt->execute();
        $resultt = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
        if ($resultt){
            echo"<h1 class='nonerror'> Vitaj na stránke {$resultt[0]->getMeno()} {$resultt[0]->getPriezvisko()} </h1>";
            echo'<div class="tlacidla">
                        <button class="btn btn-primary" onclick="location.href=\'../index.php\'" type="button">
                            Návrat späť na úvodnú stránku
                        </button>
                        ';
            if ($resultt[0]->getTyp()!="google")
                echo'<form action="index.php" method="post" enctype="multipart/form-data">
                            <input class="btn btn-primary" type="submit" value="logout" name="logout">
                        </form>
                 </div>';
            else
                echo '<button class="btn btn-primary" onclick="location.href=\'../oauth/logout.php\'" type="button">
                    Logout
                </button>';
            echo"<h2>Osobné údaje:</h2>";
            echo"Meno: {$resultt[0]->getMeno()}<br>
                             Priezvisko: {$resultt[0]->getPriezvisko()}<br>
                             Email: {$resultt[0]->getEmail()}<br>
                             Typ účtu: {$resultt[0]->getTyp()}<br>";


        }

        $idcko=$resultt[0]->getId();
        echo'<div class="tlacidla">
                    <button onclick="show_div();" class="btn btn-primary">Zobraziť históriu prihlásení</button>
                </div>';
        $stmt = $conn->prepare("select time  from pripojenia 
                                where users_id='$idcko'");
        $stmt->execute();
        $result1 = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
        echo'<div id="historia"> 
                    <h2 class="nonerror">História prihlásení na tvojom účte:</h2>
                    <table class="tabulka" id="tabulka">
                    <thead>
                        <tr>
                            <th>Čas prihlásenia</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($result1 as $hist){
            echo "<tr>
                    <td>{$hist->getTime()}</td>
                    </tr>";
        }
        echo'</tbody></table>';
        echo'<h2 class="nonerror">Štatistika jednotlivých typov prihlásení </h2>';
        $stmt = $conn->prepare("select users.typ,count(*) as pocet  from (pripojenia
                                join users ON users.id=pripojenia.users_id ) group by users.typ order by users.typ asc
                                ");
        $stmt->execute();
        $result1 = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
        echo'<table class="tabulka" id="tabulka2">
                    <thead>
                        <tr>
                            <th>Klasické prihlásenie</th>
                            <th>Ldap</th>
                            <th>Google</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($result1 as $r){
            if ($r->getTyp()==="normal")
                $x[0]=$r->getPocet();
            if ($r->getTyp()==="ldap")
                $x[1]=$r->getPocet();
            if ($r->getTyp()==="google")
                $x[2]=$r->getPocet();
        }
        if (!(isset($x[0])))
            $x[0]=0;
        if (!(isset($x[1])))
            $x[1]=0;
        if (!(isset($x[2])))
            $x[2]=0;

        echo "<tr>
                    <td>{$x[0]}</td>
                    <td>{$x[1]}</td>                   
                    <td>{$x[2]}</td>                                                        
                  </tr>";
        echo'</tbody></table></div>';
    }catch(PDOException $e) {
        echo "<p>Error: " . $e->getMessage();
    }
    $conn = null;
}
    ?>
</body>
</html>