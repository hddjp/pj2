<!DOCTYPE html>
<?php require_once('../../config.php');

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
        $content[$row['ImageID']]=$row['Content'];
        $picinfo['city'][$row['ImageID']]=$row['CityCode'];
        $picinfo['country'][$row['ImageID']]=$row['CountryCodeISO'];
        $picinfo['who'][$row['ImageID']]=$row['UID']; 
    }
}
catch (PDOException $e) {
    die( $e->getMessage() );
}
$picinfo['title']=$title;
$picinfo['description']=$description;
$picinfo['path']=$path;
$picinfo['theme']=$content;

$isfavored=false;
//判断当前用户是否收藏该图片
if(isset($_COOKIE['Username'])){
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from traveluser where UserName='".$_COOKIE['Username']."'";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            $userid=$row['UID'];
        }
        $sql="select * from travelimagefavor where UID=".$userid." and ImageID=".$_GET['id']."";
        if($pdo->query($sql)->rowCount()!=0){
            $isfavored=true;
        };
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function booltostr($bool){
    //将布尔型值转为true和false，后面需要使用
    return $bool?"true":"false";
}

?>

<html>
    <head>
        <title>图片详情</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../../reset.css" type="text/css">
        <link rel="stylesheet" href="../css/details-common.css" type="text/css">
        <link media="(max-width:1320px) and (min-width:1120px)" rel="stylesheet" href="../css/details-middle.css" type="text/css">
        <link media="(max-width:1120px) and (min-width:600px)" rel="stylesheet" href="../css/details-small.css" type="text/css">
        <link media="(max-width:600px)" rel="stylesheet" href="../css/details-mobile.css" type="text/css">
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
        <?php 
        //打印当前图片的相关信息。url中的id属性代表图片
        $id=$_GET['id'];
        try{
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($picinfo['city'][$id]!=null){
                $sql="select * from geocities where GeoNameID=".$picinfo['city'][$id];
                $result=$pdo->query($sql);
                while($row=$result->fetch()){
                    $city=$row['AsciiName'];
                }
            }   
            if($picinfo['country'][$id]!=null){
                $sql="select * from geocountries where ISO='".$picinfo['country'][$id]."'";
                $result=$pdo->query($sql);
                while($row=$result->fetch()){
                    $country=$row['CountryName'];
                }
            }
            $sql="select * from traveluser where UID=".$picinfo['who'][$id];
            $result=$pdo->query($sql);
            while($row=$result->fetch()){
                $photoer=$row['UserName'];
            }
            $favor=0;
            $sql="select * from travelimagefavor where ImageID= ".$id;
            $result=$pdo->query($sql);
            while($row=$result->fetch()){
                $favor++;
            }
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
        echo "<h1>".$picinfo['title'][$id]."</h1>";
        echo "<h2>收藏数：<span>".$favor."</span></h2>";
        echo "<button class='favor'>";
        echo "<img src='../../img/others/favorate2.png' class='favor'>收藏";
        echo "</button>";
        echo "<hr>";
        echo "<table class='table1' id='fav'>";
        echo "<tr>";
        echo "<td>";
        echo "<div class='picture'>";
        echo "<img src='../../img/normal/medium/".$picinfo['path'][$id]."' class='picture'>";
        echo "</div>";
        echo "</td>";
        echo "<td name='infor'>";
        echo "<table class='table2'>";
        echo "<tr><td>拍摄者：</td><td>".$photoer."</td></tr>";
        echo "<tr><td>主题：</td><td>".$picinfo['theme'][$id]."</td></tr>";
        if(isset($country)){
            echo "<tr><td>拍摄国家：</td><td>".$country."</td></tr>";
        }else{
            echo "<tr><td>拍摄国家:</td><td>未知</td></tr>";
        }
        if(isset($city)){
            echo "<tr><td>拍摄城市：</td><td>".$city."</td></tr>";
        }else{
            echo "<tr><td>拍摄城市:</td><td>未知</td></tr>";
        }
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        if($picinfo['description'][$id]!=null){
            echo "<p id='description'>".$picinfo['description'][$id]."</p>";
        }else{
            echo "<p id='description'>This is a beautiful picture</p>";
        }
        echo "<hr>";
        echo "<script>
        var isfavored=".booltostr($isfavored).";//如果图片被收藏则显示取消收藏的按钮，如果没有则显示收藏按钮
        var login=".booltostr(isset($_COOKIE['Username'])).";
        var id=".$id.";
        </script>";
       ?>
        <p><br><br><br><br><br><br><br><br></p>
        </main>
        <footer>
            文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com
        </footer>
    </body>
    <script src="../javascript/details.js"></script>
</html>