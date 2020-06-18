<?php 
require_once("../../config.php");

if($_GET['type']=='popcountry'){
    //找popular国家
    $IDs="";
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from travelimage where CountryCodeISO = '".$_GET['name']."'";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            $IDs.=$row['ImageID'];
            $IDs.="x";//发现在cookie里面&符号会变成%26，%也变成%26，#变成%23，空格变成+
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
    if($IDs!=''){
        $IDs=rtrim($IDs,"x");
    } 
    
    setcookie("browser",$IDs,time()+60*60*24,"/pj2");
}
if($_GET['type']=='popcity'){
    //找popular城市
    $IDs="";
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from travelimage where CityCode = '".$_GET['name']."'";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            $IDs.=$row['ImageID'];
            $IDs.="x";
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
    if($IDs!=''){
        $IDs=rtrim($IDs,"x");
    } 
    setcookie("browser",$IDs,time()+60*60*24,"/pj2");
}
if($_GET['type']=='poptheme'){
    //找popular主题
    $IDs="";
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from travelimage where Content = '".$_GET['name']."'";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            $IDs.=$row['ImageID'];
            $IDs.="x";
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
    if($IDs!=''){
        $IDs=rtrim($IDs,"x");
    } 
    setcookie("browser",$IDs,time()+60*60*24,"/pj2");
}
if($_GET['type']=='cleancookie'){
    setcookie("browser","",-1,"/pj2");
}
if($_GET['type']=='logout'){
    setcookie("Username","",-1,"/pj2");
}
if($_GET['type']=='select'){
    setcookie("browser",$_GET['value'],time()+60*60*24,"/pj2");
}
?>