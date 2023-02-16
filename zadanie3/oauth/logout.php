<?php
session_start();
define('MYDIR','../googleapi/');
require_once(MYDIR."vendor/autoload.php");
$client = new Google_Client();
$client->setAuthConfig('../../../../configss/credentials.json');
unset($_SESSION['upload_token']);
$client->revokeToken();
session_destroy();
header("Location:../index.php");
?>