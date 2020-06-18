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

    if($_GET['con']=='true'){
        $sql="delete from travelimagefavor where UID=".$uid." and ImageID=".$_GET['id'];
    }else{
        $sql="insert into travelimagefavor (UID,ImageID) values (".$uid.",".$_GET['id'].")";
    }
    $pdo->exec($sql);
}
catch (PDOException $e) {
    die( $e->getMessage() );
}
?>