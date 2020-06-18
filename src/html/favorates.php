<!DOCTYPE html>
<?php
require_once("../../config.php");

function shownav(){
    if(isset($_COOKIE['Username'])){
        echo "<div class='dropdown'>";
        echo "<a class='dropbt'>个人中心";
        echo "<img class='list' src='../../img/others/list.png' width='20px' height='20px' >";
        echo " </a>";
        echo "<div class='dropdown-content'>";
        echo "<a href='./upload.php' class='box'>";
        echo "<img class='manu' src='../../img/others/upload.png' width='25px' height='25px' >上传";
        echo "</a>";
        echo "<a href='./mypic.php' class='box'>";
        echo "<img class='manu' src='../../img/others/mypic.png' width='25px' height='25px'>我的照片";
        echo "</a>";
        echo "<a href='./favorates.php' class='box'>";
        echo "<img class='manu' src='../../img/others/favorate.png' width='25px' height='25px'>我的收藏";
        echo "</a>";
        echo "<a id='logout' href=''>";
        echo "<img class='manu' src='../../img/others/logout.png' width='25px' height='25px' >登出";
        echo "</a>";
        echo "</div>";
        echo "</div>";
    }
    else{
        echo "<div class='dropdown'>";
        echo "<a class='dropbt box' href='signin.php'>登录"; 
        echo "</a>";
        echo "<img class='list' src='../../img/others/login.png' width='25px' height='25px'";
        echo "</div>";
    }
}

function getfavors(){
    //从数据库中获取哪些照片被当前用户收藏
    $favors=array();
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from traveluser where UserName='".$_COOKIE['Username']."'";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $uid=$row['UID'];
    }
    $sql="select * from travelimagefavor where UID=".$uid;
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $favors[]=$row['ImageID'];
    }
    return $favors;
}

$picinfo=array();
function showpic($picarray){
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from travelimage";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        if(in_array($row['ImageID'],$picarray)){
            $picinfo['path'][$row['ImageID']]=$row['PATH'];
            $picinfo['title'][$row['ImageID']]=$row['Title'];
            $picinfo['description'][$row['ImageID']]=$row['Description'];
        }
    }
    $num=sizeof($picarray);
    if($num>5*6){
        $num=5*6;
    }
    $pages=(int)(($num-1)/6+1);
    if(isset($_GET['page'])){
        $p=$_GET['page'];
    }else{$p=1;}
    if($p==$pages){
        echo "<table>";
        for($i=($p-1)*6;$i<$num;$i++){
            echo "<tr>";
            echo "<td>";
            echo "<div class='pic'>";
            echo "<img src='../../img/normal/medium/".$picinfo['path'][$picarray[$i]]."' class='pic picture' onclick=\"window.location.href='details.php?id=".$picarray[$i]."'\">";
            echo "</div>";
            echo "</td>";
            echo "<td>";
            echo "<p class='title'  onclick=\"window.location.href='details.php?id=".$picarray[$i]."'\">".$picinfo['title'][$picarray[$i]]."</p>";
            if($picinfo['description'][$picarray[$i]]!=null){
                echo "<p class='description'>".$picinfo['description'][$picarray[$i]]."</p>";
            }else{
                echo "<p class='description'>This is a beautiful picture.</p>";
            }
            echo "</td>";
            echo "<td>" ;
            echo "<button class='unfavorate butt' title='取消收藏' id=".$picarray[$i].">";
            echo "<img class='unfavorate' src='../../img/others/unfavorate.png'>";
            echo "</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "<tr><td></td><td><p class='hidden'>abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz</p></td></tr>";
        echo "</table>";
    }else{
        echo "<table>";
        for($i=($p-1)*6;$i<$p*6;$i++){
            echo "<tr>";
            echo "<td>";
            echo "<div class='pic'>";
            echo "<img src='../../img/normal/medium/".$picinfo['path'][$picarray[$i]]."' class='pic' onclick=\"window.location.href='details.php?id=".$picarray[$i]."'\">";
            echo "</div>";
            echo "</td>";
            echo "<td>";
            echo "<p class='title'  onclick=\"window.location.href='details.php?id=".$picarray[$i]."'\">".$picinfo['title'][$picarray[$i]]."</p>";
            if($picinfo['description'][$picarray[$i]]!=null){
                echo "<p class='description'>".$picinfo['description'][$picarray[$i]]."</p>";
            }else{
                echo "<p class='description'>This is a beautiful picture.</p>";
            }
            echo "</td>";
            echo "<td>" ;
            echo "<button class='unfavorate butt' title='取消收藏'  id=".$picarray[$i].">";
            echo "<img class='unfavorate' src='../../img/others/unfavorate.png'>";
            echo "</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "<tr><td></td><td><p class='hidden'>abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz</p></td></tr>";
        echo "</table>";
    }
    if($p==1){
        $last=1;
    }else{
        $last=$p-1;
    }
    if($p==$pages){
        $next=$pages;
    }else{
        $next=$p+1;
    }
    echo "<div id='page'>";
    echo "<a href='favorates.php?page=".$last."' class='page'>&lt</a>";
    for($i=0;$i<$pages;$i++){
        echo "<a href='favorates.php?page=".($i+1)."' class='pagenum'>".($i+1)."</a>";
    }
    echo "<a href='favorates.php?page=".$next."' class='page'>&gt</a>";
    echo "</div>";
}
?>
<html>
    <head>
        <title>收藏夹</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../../reset.css" type="text/css">
        <link rel="stylesheet" href="../css/favorates-common.css" type="text/css">
        <link media="(max-width:1370px) and (min-width:1170px)" rel="stylesheet" href="../css/favorates-middle.css" type="text/css">
        <link media="(max-width:1170px) and (min-width:600px)" rel="stylesheet" href="../css/favorates-small.css" type="text/css">
        <link media="(max-width:600px)" rel="stylesheet" href="../css/favorates-mobile.css" type="text/css">
    </head>
    <body>
        <header>
            <nav>
                <a href="../../index.php" class="navigation" name="index">首页</a>
                <a href="browser.php" class="navigation" name="browser">浏览页</a>
                <a href="search.php" class="navigation" name="search">搜索页</a>
                <?php shownav();?>
            </nav>
        </header>
        <main>
            <h1>
                <img src="../../img/others/favorate1.png">
                收藏夹
            </h1>
            <?php
            if(!isset($_COOKIE['Username'])){
                //先判断有无登录
                echo "<p id='nothing'>请登录您的账号！</p>";
            }else{
                $favors=getfavors();
                if(sizeof($favors)==0){
                    echo "<p id='nothing'>收藏夹里空空如也！</p>";
                }else{
                    showpic($favors);
                }
            }
            ?>
            <p><br></p>
        </main>
        <footer>
            文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com
        </footer>
        <script src="../javascript/favorates.js"></script>
    </body>
</html>            