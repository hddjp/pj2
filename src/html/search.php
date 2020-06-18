<!DOCTYPE html>
<?php
require_once("../../config.php");
//search页面内容基本和browser的搜索功能差不多
$picinfo=array();
try{
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from travelimage";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $title[$row['ImageID']]=$row['Title'];
        $description[$row['ImageID']]=$row['Description'];
        $path[$row['ImageID']]=$row['PATH'];
    }
}
catch (PDOException $e) {
    die( $e->getMessage() );
}
$picinfo['title']=$title;
$picinfo['description']=$description;
$picinfo['path']=$path;

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

function showpic($pics){
    global $picinfo;
    if($pics=="nothing"){
        echo "<p id='nothing'>没有找到相关图片</p>";
    }else{
        $picarray=explode("x",$pics);
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
                echo "</tr>";
            }
            echo "<tr><td></td><td><p class='hidden'>abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz</p></td></tr>";
            //用隐藏文字让表格宽度无论何时都一样
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
        echo "<a href='search.php?page=".$last."' class='page'>&lt</a>";
        for($i=0;$i<$pages;$i++){
            echo "<a href='search.php?page=".($i+1)."' class='pagenum'>".($i+1)."</a>";
        }
        echo "<a href='search.php?page=".$next."' class='page'>&gt</a>";
        echo "</div>";
    }
}
?>
<html>
    <head>
        <title>搜索页</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../../reset.css" type="text/css">
        <link rel="stylesheet" href="../css/search-common.css" type="text/css">
        <link media="(max-width:1320px) and (min-width:1120px)" rel="stylesheet" href="../css/search-middle.css" type="text/css">
        <link media="(max-width:1120px) and (min-width:600px)" rel="stylesheet" href="../css/search-small.css" type="text/css">
        <link media="(max-width:600px)" rel="stylesheet" href="../css/search-mobile.css" type="text/css">
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
            <h1>请搜索您想要查找的图片</h1>
            <div class="search">
                <form action='' method='post' role='form' id='form'>
                    <select id="way" class="search" name="way">
                        <option value="title" selected>标题筛选</option>
                        <option value="description">描述筛选</option>
                    </select>
                    <?php 
                    echo "<input type='text' class='search' name='search' placeholder='search ".sizeof($picinfo['title'])." pics' autofocus>";
                    ?>
                    <input type="submit" value="" class="search">
                </form>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST"){
                    $IDs="";
                    $key=$_POST['search'];
                    $keywords=preg_split("/[\s,]+/",$key);
                    switch($_POST['way']){
                        case "title": 
                            $sql="select * from travelimage where title is not null ";
                            foreach($keywords as $keyword){
                                $sql.="and title like '%".$keyword."%'";
                            }
                            break;
                        case "description": 
                            $sql="select * from travelimage where Description is not null ";
                            foreach($keywords as $keyword){
                                $sql.="and Description like '%".$keyword."%'";
                            }
                            break;   
                    }
                    try{
                        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $result=$pdo->query($sql);
                        while($row=$result->fetch()){
                            $IDs.=$row['ImageID']."x";
                        }
                    }
                    catch (PDOException $e) {
                        die( $e->getMessage() );
                    }
                    if($IDs!=''){
                        $IDs=rtrim($IDs,"x");
                    }else{
                        $IDs='nothing';
                    }
                    echo "<script>
                    let xhr=new XMLHttpRequest();
                    let url='../php/search1.php?type=search&value=".$IDs."';
                    xhr.open('get',url,false);
                    xhr.send(null);
                    window.location.href='search.php?page=1'</script>";
                }
                ?>
            </div>
            <?php
            if(isset($_COOKIE['search'])){
                showpic($_COOKIE['search']);
            }else{
                $ranstr='';
                $all=range(1,sizeof($picinfo['title']));
                $ranarr=array_rand($all,6);
                for($i=0;$i<6;$i++){
                    $ranstr.=($ranarr[$i]+1).'x';
                }
                $ranstr=rtrim($ranstr,"x");
                showpic($ranstr);
            }
            ?>
            <p><br></p>
        </main>
        <footer>
            文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com
        </footer>
    </body>
    <script src="../javascript/search.js"></script>
</html>