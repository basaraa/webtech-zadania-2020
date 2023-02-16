<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Prihlásenia cez rôzne aplikácie</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="index.php">Domov</a></li>
            <li><a href="2FA_auth/registracia_form.php">Registrácia</a></li>
        </ul>
    </nav>
    <hr>
</header>
<h1 class="nonerror">Vitajte na stránke</h1>
<p>Máte možnosť sa prihlásiť cez 3 rôzne prihlásenia</p>
<p>Súčasne môžete byť prihlásený len na 1 účte</p>
<p>Pre prihlásenie sa na iný účet alebo na využitie iného spôsobu prihlásenia je nutné sa najprv odhlásiť</p>
<div class="tlacidla">
    <button class ="btn btn-primary" onclick="location.href='2FA_auth/'">Vlastné prihlásenie</button>
    <button class ="btn btn-primary" onclick="location.href='ldap/'">Stuba prihlásenie</button>
    <button class ="btn btn-primary" onclick="location.href='oauth/'">Google prihlásenie</button>
</div>

<?php
?>
<script src="scripts/Scripts.js"></script>
</body>
</html>
