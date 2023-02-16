<!DOCTYPE html>
<html lang="sk">
<head>
    <title>ldap prihlásenie</title>
<link rel="shortcut icon" type="image/jpg" href="../img/favicon.ico"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
<link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="../index.php">Domov</a></li>
            <li><a href="../2FA_auth/registracia_form.php">Registrácia</a></li>
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
    if(isset($_POST['Nickname_S'])){
        $ldapconfig['host'] = 'ldap.stuba.sk';
        $ldapconfig['port'] = '389';
        $ldapconfig['basedn'] = 'ou=People, DC=stuba, DC=sk';
        $ldapconfig['usersdn'] = 'cn=users';
        $ds=ldap_connect($ldapconfig['host'], $ldapconfig['port']);
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 10);
        $username = $_POST['Nickname_S'];
        $password = $_POST['Heslo_S'];
        $dn="uid=".$username.",".$ldapconfig['basedn'];
        if ($bind=@ldap_bind($ds, $dn, $password)) {
            echo"<br>";
            $search=ldap_search($ds,$ldapconfig['basedn'],'uid='.$username,['givenname','surname','mail']);
            $result=ldap_get_entries($ds,$search);
            $typ="ldap";
            $meno=$result[0]['givenname'][0];
            $priezvisko=$result[0]['sn'][0];
            $email=$result[0]['mail'][0];
            require_once "../triedy/reg_class.php";
            require_once "../triedy/databaza/Database.php";
            try {
                $conn = (new Database())->Napojenie();
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql0="select * from users 
                        where meno='$meno' and priezvisko='$priezvisko' 
                          and email='$email' and typ='$typ'";
                $result=$conn->query($sql0);
                if ($result->rowCount()==0){
                    $sql = "INSERT INTO users (meno,priezvisko,email,typ)
                    VALUES ('$meno','$priezvisko', '$email','$typ')";
                    $conn->exec($sql);
                }
                $stmt=$conn->prepare("select * from users 
                        where meno='$meno' and priezvisko='$priezvisko' 
                          and email='$email' and typ='$typ'");
                $stmt->execute();
                $resultt = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
                if ($resultt){
                    $time = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO pripojenia (users_id,time)
                        VALUES ('{$resultt[0]->getId()}','$time')";
                    $conn->exec($sql);
                }
            } catch(PDOException $e) {
                echo "<p>Error: " . $e->getMessage();
            }
            $stmt = $conn->prepare("select * from users 
                        where email='$email' and meno='$meno' and priezvisko='$priezvisko' 
                        and typ='ldap'");
            $stmt->execute();
            $resultt = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
            $_SESSION['id']=$resultt[0]->getId();
            if ($resultt){
                echo"<h1 class='nonerror'> Vitaj na stránke {$resultt[0]->getMeno()} {$resultt[0]->getPriezvisko()} </h1>";
                echo("<div class='tlacidla'>
                <button class='btn btn-primary' onclick=\"location.href='../index.php'\">Návrat späť na úvodnú stránku</button>
               
                ");
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
            echo'<div class="tlacidla">
                    <button onclick="show_div()" class="btn btn-primary">Zobraziť históriu prihlásení</button>
                </div>';
            $idcko=$resultt[0]->getId();
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

            $conn = null;

        }
        else {
            echo "Zlyhanie prihlásenia - zadali ste zlé meno alebo heslo";
            echo("<div class='tlacidla'>
                <button class='btn btn-primary' onclick=\"location.href='index.php'\">Znova sa prihlásiť</button>
                </div>
                ");
        }
        ldap_close($ds);
    }
    else{
        echo'<form action="index.php" method="post">
                <label for="Nickname_S">Zadaj meno:</label><br>
            <input class="form-control" type="text" name="Nickname_S" id="Nickname_S" required><br>
            <label for="Heslo_S">Zadaj heslo:</label><br>
            <input class="form-control" type="password" name="Heslo_S" id="Heslo_S" required><br>
            <div class="tlacidla">
                <input class="btn btn-primary" type="submit" value="Prihlásiť sa">
            </div>
        </form>';
    }
}
else{
    require_once "../triedy/databaza/Database.php";
    require_once "../triedy/reg_class.php";
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
    }catch(PDOException $e) {
        echo "<p>Error: " . $e->getMessage();
    }
    $conn = null;
}
?>

</body>
</html>
