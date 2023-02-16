<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Google prihlásenie</title>
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
define('MYDIR','../googleapi/');
require_once(MYDIR."vendor/autoload.php");

$redirect_uri = 'https://wt116.fei.stuba.sk/WebTt2zadania/zadanie3/oauth';

$client = new Google_Client();
$client->setAuthConfig('../../../../configss/credentials.json');
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");
      
$service = new Google_Service_Oauth2($client);
			
if(isset($_GET['code'])){
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);
  $_SESSION['upload_token'] = $token;
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

if (!empty($_SESSION['upload_token'])) {
  $client->setAccessToken($_SESSION['upload_token']);
  if ($client->isAccessTokenExpired()) {
    unset($_SESSION['upload_token']);
  }
} else {
  $authUrl = $client->createAuthUrl();
}

if ($client->getAccessToken()) {
    $UserProfile = $service->userinfo->get();
    if(!empty($UserProfile)){
        $output = '<h1 class="nonerror">Vitaj na stránke  </h1>'.$UserProfile['given_name'].' '.$UserProfile['family_name'];
        $output .= '<h2>Osobné údaje </h2>';
        $output .= '<br/>Meno: ' . $UserProfile['given_name'];
        $output .= '<br/>Priezvisko: ' .$UserProfile['family_name'];
        $output .= '<br/>Email : ' . $UserProfile['email'];
        $output .= '<button class="btn btn-primary" onclick="location.href=\'logout.php\'" type="button">
                    Logout
                </button>';
        $meno=$UserProfile['given_name'];
        $priezvisko=$UserProfile['family_name'];
        $email=$UserProfile['email'];
        $typ="google";
        $gid=$UserProfile['id'];
        require_once "../triedy/reg_class.php";
        require_once "../triedy/databaza/Database.php";

        try {
            $conn = (new Database())->Napojenie();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql0="select * from users 
                    where meno='$meno' and priezvisko='$priezvisko' 
                      and email='$email' and typ='$typ' and google_id='$gid'";
            $result=$conn->query($sql0);
            if ($result->rowCount()==0){
                $sql = "INSERT INTO users (meno,priezvisko,email,typ,google_id)
                VALUES ('$meno','$priezvisko', '$email','$typ','$gid')";
                $conn->exec($sql);
            }
            $stmt=$conn->prepare("select * from users 
                    where meno='$meno' and priezvisko='$priezvisko' 
                      and email='$email' and typ='$typ' and google_id='$gid'");
            $stmt->execute();
            $resultt = $stmt->fetchAll(PDO::FETCH_CLASS,"reg_class");
            if ($resultt){
                $_SESSION['id']=$resultt[0]->getId();
                $time = date('Y-m-d H:i:s');
                $sql0="select * from pripojenia 
                    where time='$time' and users_id='{$resultt[0]->getId()}'";
                $result=$conn->query($sql0);
                if ($result->rowCount()==0){
                    $sql = "INSERT INTO pripojenia (users_id,time)
                    VALUES ('{$resultt[0]->getId()}','$time')";
                    $conn->exec($sql);
                }
            }
            $idcko=$resultt[0];
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
        } catch(PDOException $e) {
            echo "<p>Error: " . $e->getMessage();
        }
        $conn = null;
    }else{
        $output = "<h1 class='error'>Some problem occurred, please try again.</h1><div class='tlacidla'>
                <button class='btn btn-primary' onclick=\"location.href='index.php'\">Znova sa prihlásiť</button>
                </div>";
    }   
  } else {
      $authUrl = $client->createAuthUrl();
      $output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL). '"><img src="../img/google_sign.png" id="google_sign" alt="google pripojenie"></a>';
  }
    echo"<div>$output</div>";
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
                echo '<button class="btn btn-primary" onclick="location.href=\'logout.php\'" type="button">
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