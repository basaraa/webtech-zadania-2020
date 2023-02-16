<?php
require_once "triedy/databaza/Database.php";
$ch = curl_init();
$conn = (new Database())->Napojenie();
curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/apps4webte/curldata2021/contents");
curl_setopt($ch,CURLOPT_USERAGENT,'basaraa');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$repo = curl_exec($ch);
$repo=json_decode($repo,true);
$lines=explode(PHP_EOL,"");
foreach($repo as $subor){
    $NazovSuboru=$subor['name'];
    $datum=mb_substr($NazovSuboru, 0, 8);
    $checkdatum=date_create_from_format('Ymd',$datum)->format('Y-m-d');
    $sql ="SELECT timestamp_p
                FROM prednasky where timestamp_p='$checkdatum'";
    $result = mysqli_query($conn,$sql);

    if ($result->num_rows===0){
        $stmt =$conn->prepare("INSERT INTO prednasky (timestamp_p)
                VALUES (?)") ;
        $stmt->bind_param('s', $datum);
        $stmt->execute();
        $chs = curl_init();
        curl_setopt($chs, CURLOPT_URL, "https://raw.githubusercontent.com/apps4webte/curldata2021/main/".$NazovSuboru);
        curl_setopt($chs, CURLOPT_RETURNTRANSFER, 1);
        $output=curl_exec($chs);
        $output=mb_convert_encoding($output,'UTF-8','UTF-16LE');
        $lines=explode(PHP_EOL,$output);
        $csv=[];
        $sql="select id from prednasky ORDER BY id DESC LIMIT 1";
        $resultp = mysqli_query($conn,$sql);
        $xs=mysqli_fetch_assoc($resultp);
        $id=$xs['id'];
        $stmt =$conn->prepare("INSERT INTO ucast_studentov (prednasky_id,meno,action,timestamp)
                VALUES (?,?,?,?)") ;
        $stmt->bind_param('dsss', $id,$meno, $action, $timestamp);

        foreach($lines as $i=>$line){
            $linestring=str_getcsv($line,"\t");
            if ($i>0&&$linestring[0]){
                $meno=$linestring[0];
                $action=$linestring[1];
                if (strpos($linestring[2], 'AM'))
                    $timestamp=date("Y-m-d H:i:s",date_create_from_format('d/m/Y, H:i:s A',$linestring[2])->getTimestamp());
                else
                    $timestamp=date("Y-m-d H:i:s",date_create_from_format('d/m/Y, H:i:s',$linestring[2])->getTimestamp());
                $stmt->execute();
            }
        }
    }
}
curl_close($ch);
$conn = null;
echo json_encode(["status"=>"success","msg"=>"bolo pridanych ".sizeof($lines)." riadkov"]);
?>