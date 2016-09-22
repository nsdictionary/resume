<?php
$upload_dir = "./brille_image/";
$img = $_POST['imgBase64'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$timeStamp = $timestamp = strtotime(date("Y-m-d H:i:s"));
$file = $upload_dir."/".$timeStamp.".png";
$success = file_put_contents($file, $data);
echo $timeStamp.".png";
// header('Location: '.$_POST['return_url']);
?>