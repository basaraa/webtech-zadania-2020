<?php
if (!(isset($conn))){
    require_once "databaza/Database.php";
    $conn = (new Database())->Napojenie();
}
if (!(isset($query))){
    $query=array();
}
if (!(isset($ip)))
    $ip="1";
$timezone=$query['timezone'];
$dt = new DateTime("now", new DateTimeZone($timezone));
$time=$dt->format('H:i');
$x=explode(":",$time);
$y=(int)$x[0];
$typ=0;
if ($y>=6& $y<15)
    $typ=1;
else if ($y>=15& $y<21)
    $typ=2;
else if ($y>=21& $y<24)
    $typ=3;
else
    $typ=4;
$counter=0;
$visit=0;
$sql ="SELECT * FROM ip_info where IP='$ip'";
$result = mysqli_query($conn,$sql);
if ($result->num_rows===0){
    $stmt =$conn->prepare("INSERT INTO ip_info (IP)
                VALUES (?)") ;
    $stmt->bind_param('s', $ip);
    $stmt->execute();
    $sql ="SELECT * FROM ip_info where IP='$ip'";
    $result = mysqli_query($conn,$sql);
    $result = mysqli_fetch_assoc($result);
    $ip_id=$result['id'];
    $code=$query['countryCode'];
    $country=$query['country'];
    $city=$query['city'];
    $lat=$query['lat'];
    $lon=$query['lon'];
    $stmt =$conn->prepare("INSERT INTO location_info (IP_id,country,country_code,city,lat,lon)
                VALUES (?,?,?,?,?,?)") ;
    $stmt->bind_param('dsssss', $ip_id,$country,$code,$city,$lat,$lon);
    $stmt->execute();
    $visit=1;
}
else
    $result = mysqli_fetch_assoc($result);
$ip_id=$result['id'];
$stmt =$conn->prepare("INSERT IGNORE INTO time_count (typ,counter)
                VALUES (?,?)") ;
$stmt->bind_param('dd', $typp,$counter);
$typp=1;
$stmt->execute();
$typp=2;
$stmt->execute();
$typp=3;
$stmt->execute();
$typp=4;
$stmt->execute();
if ($visit===1){

    $stmt =$conn->prepare("UPDATE time_count SET counter = counter + 1 WHERE typ = '$typ'") ;
    $stmt->execute();
}
$stmt =$conn->prepare("INSERT INTO visits (IP_id,page_visit)
                VALUES (?,?)") ;
$stmt->bind_param('ds', $ip_id,$page);
$stmt->execute();