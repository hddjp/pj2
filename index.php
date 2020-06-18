<!DOCTYPE html lang="en">
<?php require_once('config.php');?>
<?php

$picshow=array();//一个数组，里面的元素为按照收藏数排好的图片id
$picfavor=array();//保存所有图片的收藏人数，

$picinfo=array();//关于图片的标题、描述、路径等信息
try{
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from travelimage";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $title[$row['ImageID']]=$row['Title'];
        $description[$row['ImageID']]=$row['Description'];
        $path[$row['ImageID']]=$row['PATH'];
        $picfavor[$row['ImageID']]=0;
    }
}
catch (PDOException $e) {
    die( $e->getMessage() );
}
$picinfo['title']=$title;
$picinfo['description']=$description;
$picinfo['path']=$path;

function initialPic(){
    //初始化，在网页加载时运行此函数，得到要展示的图片
    global $picshow;
    global $picfavor;
    $mostfavor=array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
    //mostfavor为长度为6的数组，键为图片id，值为收藏的人数。通过遍历将收藏最多的6张图片放入数组，用于初始显示
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from travelimagefavor";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            $picfavor[$row['ImageID']]++;
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
    foreach($picfavor as $key=>$value){
        $plugin=false;
        foreach($mostfavor as $id1=>$favor1){
            if($key==$id1){
                $mostfavor[$key]=$value;
                break;
            }
            if($value>$favor1){
                $plugin=true;
                break;
            }
        }
        if($plugin){
            $min=null;
            foreach($mostfavor as $id2=>$favor2){
                if($min===null||$min>$favor2){
                    $min=$favor2;
                    $minid=$id2;
                }
            }
            
            unset($mostfavor[$minid]);
            $mostfavor[$key]=$value;
        }
    }
    while(sizeof($mostfavor)!=0){
        $max=null;
        foreach($mostfavor as $key=>$value){
            if($max===null||$max<$value){
                $max=$value;
                $maxid=$key;
            }
        }
        unset($mostfavor[$maxid]);
        $picshow[]=$maxid;
    }
}

function randomPic(){
    //随机取一张图片
    global $picinfo;
    $size=sizeof($picinfo['path']);
    return mt_rand(1,$size);
}

function showpic($order){
    //展示图片的方法，此方法在其他各界面中基本上均有。
    global $picinfo;
    global $picshow;
    echo "<tr>";
    echo "<td>";
    echo "<figure class='show'>";
    echo "<div class='pic'>";
    if(isset($_COOKIE['ranpic'])&&$_COOKIE['ranpic']==1){
        //是否展示随机图片的由cookie中的ranpic值决定。当加载页面时此值不存在，点击刷新图标后ranpic变成1，保存60秒，之间刷新页面或点刷新键都随机取图片。跳转到其他页面此cookie被删除
        $a=randomPic();
        echo "<img src='img/normal/medium/".$picinfo['path'][$a]."' class='pic'  name='photo' id='image".$a."'  onclick=\"window.location.href='src/html/details.php?id=".$a."'\">";
        echo "</div>";
        echo "<figcaption onclick=\"window.location.href='src/html/details.php?id=".$a."'\">";
        if($picinfo['title'][$a]!=null){
            echo $picinfo['title'][$a];
        }else{
            echo "untitled";
        }
        echo "</figcaptionl>";
        echo "</figure>";
        echo "</td>";
        echo "<td>";
        if($picinfo['description'][$a]!=null){
            echo "<p class='show'>".$picinfo['description'][$a];
        }else{
            echo "<p class='show'>This is a picture";
        }
    }else{
        echo "<img src='img/normal/medium/".$picinfo['path'][$picshow[$order]]."' class='pic' name='photo' id='image".$picshow[$order]."' onclick=\"window.location.href='src/html/details.php?id=".$picshow[$order]."'\">";
        echo "</div>";
        echo "<figcaption onclick=\"window.location.href='src/html/details.php?id=".$picshow[$order]."'\">";
        if($picinfo['title'][$picshow[$order]]!=null){
            echo $picinfo['title'][$picshow[$order]];
        }else{
            echo "untitled";
        }
        echo "</figcaptionl>";
        echo "</figure>";
        echo "</td>";
        echo "<td>";
        if($picinfo['description'][$picshow[$order]]!=null){
            echo "<p class='show'>".$picinfo['description'][$picshow[$order]];
        }else{
            echo "<p class='show'>This is a picture";
        }
    }
    echo "</td>";
    echo "</tr>";
}

function shownav(){
    //展示导航栏，此函数在所有界面中都相同
    if(isset($_COOKIE['Username'])){
        echo "<div class='dropdown'>";
        echo "<a class='dropbt'>个人中心";
        echo "<img class='list' src='img/others/list.png' width='20px' height='20px' >";
        echo " </a>";
        echo "<div class='dropdown-content'>";
        echo "<a href='src/html/upload.php' class='box'>";
        echo "<img class='manu' src='img/others/upload.png' width='25px' height='25px' >上传";
        echo "</a>";
        echo "<a href='src/html/mypic.php' class='box'>";
        echo "<img class='manu' src='img/others/mypic.png' width='25px' height='25px'>我的照片";
        echo "</a>";
        echo "<a href='src/html/favorates.php' class='box'>";
        echo "<img class='manu' src='img/others/favorate.png' width='25px' height='25px'>我的收藏";
        echo "</a>";
        echo "<a id='logout'>";
        echo "<img class='manu' src='img/others/logout.png' width='25px' height='25px' >登出";
        echo "</a>";
        echo "</div>";
        echo "</div>";
    }
    else{
        echo "<div class='dropdown'>";
        echo "<a class='dropbt box' href='src/html/signin.php'>登录"; 
        echo "</a>";
        echo "<img class='list' src='img/others/login.png' width='25px' height='25px'";
        echo "</div>";
    }
}
initialPic();

?>
<html>
    <head>
        <title>首页</title>
        <!--用窗口宽度大小来应用不同的css样式单，实现响应式布局-->
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="reset.css" type="text/css">
        <link rel="stylesheet" href="src/css/index-common.css" type="text/css">
        <link media="(max-width:1175px)" rel="stylesheet" href="src/css/index-middle.css" type="text/css">
        <link media="(max-width:1020px)" rel="stylesheet" href="src/css/index-small.css" type="text/css">  
        <!--当宽度小于600px时将识别为移动端-->
        <link media="(max-width:600px)" rel="stylesheet" href="src/css/index-mobile.css" type="text/css"> 
    </head>
    <body>
        <header>
            <!--空div以实现回到顶部定位功能-->
            <div id="topanchor"></div>
            <!--以下为导航栏内容-->
            <nav>
                <a href="http://localhost/pj2/index.php" class="navigation" name="index">首页</a>
                <a href="http://localhost/pj2/src/html/browser.php" class="navigation" name="browser">浏览页</a>
                <a href="http://localhost/pj2/src/html/search.php" class="navigation" name="search">搜索页</a>
                <?php shownav();?>
            </nav>
        </header>
        <!--头图-->
        <figure >
                <img name="head" src="img/others/head.jpg">
        </figure>
        <main>
            <!--图片展示（因为调垂直方向边距不得不用了两个div）-->
            <div id="div1">
                <div id="div2">
                    <table name="picshow">
                        <?php showpic(0)?>
                        <?php showpic(1)?>
                        <?php showpic(2)?>
                        <?php showpic(3)?>
                        <?php showpic(4)?>
                        <?php showpic(5)?>
                    </table>
                </div>
            </div>    
        </main>
        <footer>
            <p>
                Copyright © 2001 - 2020 eproject.fudan.edu.cn All Rights Reserved. 大胖氢股份有限公司 版权所有
            </p>
            <p>
                沪网文[2020]9527-666号|新出网证(沪)字00号|ICP证沪AA-112233445566|沪公网安备 19302010036号|版权保护投诉指引
            </p>    
            <p>
                文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com 
            </p>
        </footer>
        <button name="refresh" class="float" title="刷新页面">
            
            <img src="img/others/refresh.png">
        </button>
        <button name="gotop" class="float" title="回到顶部" onclick="window.location.href='#topanchor'">
            <img src="img/others/gotop.png">
        </button>
        </body>
        <script src="src/javascript/index.js"></script>
</html>