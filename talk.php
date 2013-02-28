<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
include "programe/respond.php";
$r = array();
if(isset($_POST['input']) && !empty($_POST['input']) && isset($_POST['user']) && !empty($_POST['user'])){
    $botresponse=replybotname($_POST['input'],$_POST['user'],'Polo');
    $r = parseout(trim(preg_replace('/\s+/', ' ', $botresponse->response)));
    echo $r;
}
?>
