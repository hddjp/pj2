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

$pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
$sql="select * from traveluser where UserName='".$_COOKIE['Username']."'";
$result=$pdo->query($sql);
while($row=$result->fetch()){
    $uid=$row['UID'];
}
?>
<html>
    <head>
        <title>上传照片</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../../reset.css" type="text/css">
        <link rel="stylesheet" href="../css/upload-common.css" type="text/css">
        <link media="(max-width:1320px) and (min-width:1120px)" rel="stylesheet" href="../css/upload-middle.css" type="text/css">
        <link media="(max-width:1120px) and (min-width:600px)" rel="stylesheet" href="../css/upload-small.css" type="text/css">
        <link media="(max-width:600px)" rel="stylesheet" href="../css/upload-mobile.css" type="text/css">
    </head>
    <body>
        <header>
            <nav>
                <a href="../../index.php" class="navigation" name="index">首页</a>
                <a href="browser.php" class="navigation" name="browser">浏览页</a>
                <a href="search.php" class="navigation" name="search">搜索页</a>
                <?php shownav();?>
                </div>
            </nav>
        </header>
        <main>
            <h1>随时随地，上传你喜爱的照片</h1>
            <h2>上传的照片将被保存至“我的照片”</h2>
            <form action="" method="post" enctype="multipart/form-data">
            <?php
            //如果url中无id属性则为上传。如果有则为修改信息
            echo "<table>";
            echo "<tr>";
            echo "<td class='td1'>";
            echo "<button class='upld'>";
            $upld=isset($_GET['id'])?"修改":"上传";
            echo "<img class='upld' src='../../img/others/upload2.png'>".$upld;
            echo "</button>";
            echo "<input type='file' id='file' class='filepath' name='file' onchange='changepic(this)' accept='image/jpg,image/jpeg,image/png,image/PNG'><br>";
            $havepath=isset($_GET['id'])?"/normal/medium/".$picinfo['path'][$_GET['id']]:"/others/mypic1.png";
            echo "<div class='pic'><img id='uploadimg' src='../../img".$havepath."'></div>";
            echo "</td>";
            echo "<td>";
            echo "<p class='things'>";
            echo "<span class='things'>图片标题：</span>";
            $havetitle=(isset($_GET['id'])&&$picinfo['title'][$_GET['id']]!=null)?"value='".$picinfo['title'][$_GET['id']]."'":"placeholder='请输入图片标题'";
            echo "<input type='text' class='things' name='title' ".$havetitle.">";
            echo "<p class='things'>";
            echo "<span class='things' name='description'>图片描述：</span>";
            if(isset($_GET['id'])&&$picinfo['description'][$_GET['id']]!=null){
                echo "<textarea id='description' name='description'>".$picinfo['description'][$_GET['id']]."</textarea>";
            }else{
                echo "<textarea id='description' name='description' placeholder='请输入图片的描述'></textarea>";
            }
            echo "</p>";
            $countrycity=array();
            echo "<p class='things'>";
            echo "<span class='things'>拍摄国家：</span>";
            echo "<select class='things' name='country'>";
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $sql="select * from geocountries";
            $result=$pdo->query($sql);
            while($row=$result->fetch()){
                if(isset($_GET['id'])&&$picinfo['country'][$_GET['id']]==$row['ISO']){
                    //当修改图片，在country下拉菜单的对应国家处添加selected
                    echo "<option value='".$row['ISO']."' selected>".$row['CountryName']."</option>";
                }else{
                    echo "<option value='".$row['ISO']."'>".$row['CountryName']."</option>";
                }
                $countrycity[$row['ISO']]=array();
            }
            echo "</select>";
            echo "</p>";
            echo "<p class='things'>";
            echo "<span class='things'>拍摄城市：</span>";
            echo "<select class='things' name='city'>";
            echo "<option value=''>--</option>";

            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $sql="select * from geocities";
            $result=$pdo->query($sql);
            while($row=$result->fetch()){
                $countrycity[$row['CountryCodeISO']][$row['GeoNameID']]=$row['AsciiName'];
            }
            $str="";
            foreach($countrycity as $key=>$value){
                $str.=$key."?";
                foreach($value as $k=>$v){
                    $str.=$k;
                    $str.="%";
                    $str.=$v;
                    $str.="&";
                }
                $str=rtrim($str,"&");
                $str.="#";
                
            }
            $str=rtrim($str,"#");
            if(isset($_GET['id'])){
                $selectedcity=$picinfo['city'][$_GET['id']];
            }else{
                $selectedcity=0;
            }
            echo "<script>
            //依然是js实现二级筛选
                var str=\"".$str."\";
                var map1=new Map();
                var arr1=str.split('#');
                for(let i=0;i<arr1.length;i++){
                    map1=map1.set(arr1[i].split('?')[0],arr1[i].split('?')[1]);
                }

                var cities=document.getElementsByName('city')[0];
                var citystr=\"<option value=''>--</option>\";
                document.getElementsByName('country')[0].onchange=function(){ 
                var coun=document.getElementsByName('country')[0].value;
                var getstr=map1.get(coun);
                var arr2=getstr.split('&');
                var map2=new Map();
                for(a of arr2){
                    map2=map2.set(a.split('%')[0],a.split('%')[1]);
                }
                for(let key of map2.keys()){
                    if(key==".$selectedcity."){
                        //修改信息时给当前图片目前的city信息加selected属性
                        citystr+=\"<option selected value='\"+key+\"'>\"+map2.get(key)+\"</option>\";
                    }else{
                        citystr+=\"<option value='\"+key+\"'>\"+map2.get(key)+\"</option>\";
                    }
                }
                cities.innerHTML=citystr;
            }

            //加载时也要进行二级选择
                window.onload=function(){ 
                var coun=document.getElementsByName('country')[0].value;
                var getstr=map1.get(coun);
                var arr2=getstr.split('&');
                var map2=new Map();
                for(a of arr2){
                    map2=map2.set(a.split('%')[0],a.split('%')[1]);
                }
                for(let key of map2.keys()){
                    if(key==".$selectedcity."){
                        citystr+=\"<option selected value='\"+key+\"'>\"+map2.get(key)+\"</option>\";
                    }else{
                        citystr+=\"<option value='\"+key+\"'>\"+map2.get(key)+\"</option>\";
                    }
                }
                cities.innerHTML=citystr;
            }
            </script>";
            echo "</select>";
            echo "</p>";
            echo "<p class='things'>";
            echo "<span class='things'>图片主题：</span>";
            echo "<select class='things' name='theme'>";
            $themes=['scenery','building','city','people','wonder','animal','others'];
            foreach($themes as $theme){
                if(isset($_GET['id'])&&$picinfo['theme'][$_GET['id']]==$theme){
                    echo "<option value='".$theme."' selected>".$theme."</option>";
                }else{
                    echo "<option value='".$theme."'>".$theme."</option>";
                }
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            echo "<input type='submit'  class='submit' value='提交'>";
            
            //没有登陆时无法提交
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if(!isset($_COOKIE['Username'])){
                    echo "<script>alert('请先登录再上传')</script>";
                }else{
                    $file=$_FILES['file']; 
                    $filename=date('YmdHis').$file['name'];//加时间戳防止重名
                    $destination="../../img/normal/medium/".$filename;
                    $test=move_uploaded_file($file['tmp_name'],$destination);
                    //将图片保存到指定位置
                    if(isset($_GET['id'])){
                        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                        $sql="update travelimage set Title='".$_POST['title']."',Description='".$_POST['description']."',CityCode=".$_POST['city'].",CountryCodeISO='".$_POST['country']."',Content = '".$_POST['theme']."' where ImageID=".$_GET['id'];
                        $pdo->exec($sql);
                        if($test){
                            //如果成功保存了图片，则在数据库中添加图片路径的参数
                            $sql="update travelimage  set PATH = '".$filename."' where ImageID=".$_GET['id'];
                            $pdo->exec($sql);
                        }
                    }else{
                        //用preventdefalt实现当信息不完整时拒绝提交表单
                        echo "<script>submit.addEventListener('click',function(e){
                            if(pic.value=''){
                                alert('没有上传图片');
                                e.preventDefault();
                            }
                        });</script>";
                        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                        $sql= "select * from travelimage";
                        $n=$pdo->query($sql)->rowCount()+1;
                        $sql="insert into travelimage (ImageID,Title,Description,CountryCodeISO,CityCode,Content,PATH,UID) values (".$n.",'".$_POST['title']."','".$_POST['description']."','".$_POST['country']."',".$_POST['city'].",'".$_POST['theme']."','".$filename."',".$uid.")";
                        $pdo->exec($sql);
                    }
                    echo "<script>alert('上传成功');window.location.href='mypic.php'</script>";
                }
            }
            ?>
            </form>
        </main>
        <footer>
            文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com
        </footer>
        <!--上传照片，改变类名，上传的图片有边框-->
        <script src="../javascript/upload.js"></script>
    </body>    