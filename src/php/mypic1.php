<?php
require_once("../../config.php");
try{  
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from traveluser where Username='".$_COOKIE['Username']."'";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $uid=$row['UID'];
    }
    $sql="delete from travelimage where UID=".$uid." and ImageID=".$_GET['imgid'];
    $pdo->exec($sql);
    $afters=array();
    $sql="select * from travelimage where ImageID >".$_GET['imgid'];
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $afters[]=$row['ImageID'];
    }
    foreach($afters as $after){
        $sql="update travelimage set ImageID=".($after-1)." where ImageID=".$after;
        $pdo->exec($sql);
    }
}
catch (PDOException $e) {
    die( $e->getMessage() );
}
?>