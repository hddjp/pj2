<?php
if($_GET['type']=='search'){
    setcookie("search",$_GET['value'],time()+60*60*24,"/pj2");
}
if($_GET['type']=='cleancookie'){
    setcookie("search","",-1,"/pj2");
}
if($_GET['type']=='logout'){
    setcookie("Username","",-1,"/pj2");
}
?>