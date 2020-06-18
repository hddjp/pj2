<?php
require_once("../../config.php");
//以下为从收藏夹中移除图片的功能
try{
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from traveluser where Username='".$_COOKIE['Username']."'";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $uid=$row['UID'];
    }
    $sql="delete from travelimagefavor where UID=".$uid." and ImageID=".$_GET['imgid'];
    $pdo->exec($sql);
}
catch (PDOException $e) {
    die( $e->getMessage() );
}
?>