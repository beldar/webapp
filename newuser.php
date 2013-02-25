<?php
include "programe/respond.php";
$r = array();
if(isset($_POST['user']) && !empty($_POST['user'])){
    $user = $_POST['user'];
    $provider = $_POST['provider'];
    $q = false;
    if($user['email']!=null)  $q = "SELECT * FROM users WHERE email=".$user['email'];
    if($user['identifier']!=null) $q = "SELECT * FROM users WHERE identifier=".$user['identifier'];
    if($q){
        $res = mysql_query($q);
        if(mysql_num_rows($res)>0){
            $row = mysql_fetch_assoc($res);
            $user['id'] = $row['idusers'];
            $r['user'] = $user;
        }else{
            //create new user
            $birthday='';
            if($user['birthDay']!=null){
                $birthday = $user['birthYear']."-".$user['birthMonth']."-".$user['birthDay'];
            }
            $in = "INSERT INTO users (identifier, photoURL, displayName, firstName, gender, birthday, email, region, provider)
                VALUES('".$user['identifier']."','".$user['photoURL']."','".$user['displayName']."','".$user['firstName']."','".$user['gender']."',
                    '$birthday','".$user['email']."','".$user['region']."', '$provider')";
            $rr = mysql_query($in);
            if(mysql_errno()){
                $r['error'] = mysql_error();
            }else{
                $user['id'] = mysql_insert_id();
                $r['user'] = $user;
            }
        }
    }else{
        $in = "INSERT INTO users (identifier, photoURL, displayName, firstName, gender, birthday, email, region, provider)
                VALUES('".$user['identifier']."','".$user['photoURL']."','".$user['displayName']."','".$user['firstName']."','".$user['gender']."',
                    '','".$user['email']."','".$user['region']."', '$provider')";
        $rr = mysql_query($in);
        if(mysql_errno()){
            $r['error'] = mysql_error();
        }else{
            $user['id'] = mysql_insert_id();
            $r['user'] = $user;
        }
    }
    if(!isset($r['error'])){
        if($user['firstName']!="") $name = $user['firstName'];
        else{
            $name = $user['displayName'];
            $name = explode(" ", $name);
            $name = $name[0];
        }
        error_log("New user, greetings: NOMBRE ".$name);
        $botresponse=replybotname("NOMBRE ".$name,$user['id'],'Polo');
        $r['body'] = trim(preg_replace('/\s+/', ' ', $botresponse->response));
    }
}else
    $r['error'] = "User invalid";
echo json_encode($r);
?>
