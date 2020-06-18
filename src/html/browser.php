<!DOCTYPE html>
<?php
require_once("../../config.php");

$picpath=array();
try{
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from travelimage";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $picpath[$row['ImageID']]=$row['PATH'];
    }
}
catch (PDOException $e) {
    die( $e->getMessage() );
}

$countrycity=array();//用于存放国家城市对应关系的二维数组
$geocountry=array();
try{
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from geocountries inner join travelimage on travelimage.CountryCodeISO=geocountries.ISO";
    //找有图片的城市
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $geocountry[$row['ISO']]=$row['CountryName'];
        $countrycity[$row['CountryCodeISO']]=array();
    }
}
catch (PDOException $e) {
    die( $e->getMessage() );
}

$geocities=array();
try{
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="select * from geocities inner join travelimage on travelimage.CityCode=geocities.GeoNameID";
    $result=$pdo->query($sql);
    while($row=$result->fetch()){
        $geocities[$row['GeoNameID']]=$row['AsciiName'];
        $countrycity[$row['CountryCodeISO']][$row['GeoNameID']]=$row['AsciiName'];
    }
}
catch (PDOException $e) {
    die( $e->getMessage() );
}

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
function getPopularCountry(){
    //在快速筛选栏展示的popular country 。图片越多越popular
    global $geocountry;
    $countries=array();//countries数组的键为国家，值为图片数
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from travelimage where CountryCodeISO is not null";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){//进行计数
            if(isset($countries[$row['CountryCodeISO']])){
                $countries[$row['CountryCodeISO']]++;
            }else{
                $countries[$row['CountryCodeISO']]=1;
            }
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
    $countries['ABC']=0;$countries['DEF']=0;$countries['GHI']=0;$countries['JKL']=0;
    $maxcouns=['ABC','DEF','GHI','JKL'];//先用四个图片数为0的进行占位
    foreach($countries as $key=>$value){
        //循环遍历，进行取代
        if($value>=$countries[$maxcouns[0]]){
            $maxcouns[3]=$maxcouns[2];
            $maxcouns[2]=$maxcouns[1];
            $maxcouns[1]=$maxcouns[0];
            $maxcouns[0]=$key;
        }
        elseif($value>=$countries[$maxcouns[1]]){
            $maxcouns[3]=$maxcouns[2];
            $maxcouns[2]=$maxcouns[1];
            $maxcouns[1]=$key;
        }
        elseif($value>=$countries[$maxcouns[2]]){
            $maxcouns[3]=$maxcouns[2];
            $maxcouns[2]=$key;
        }
        elseif($value>=$countries[$maxcouns[3]]){
            $maxcouns[3]=$key;
        }
    }
    $maxcoun1=array();
    for($i=0;$i<4;$i++){
        $maxcoun1[$i]=$geocountry[$maxcouns[$i]];
    }
    echo "<p><img src='../../img/others/disc.png' name='disc2'>热门国家快速浏览</p>";
    echo "<a href='browser.php?page=1' class='popularcountry' id='".$maxcouns[0]."'>".$maxcoun1[0]."</a><br>";
    echo "<a href='browser.php?page=1' class='popularcountry' id='".$maxcouns[1]."'>".$maxcoun1[1]."</a><br>";
    echo "<a href='browser.php?page=1' class='popularcountry' id='".$maxcouns[2]."'>".$maxcoun1[2]."</a><br>";
    echo "<a href='browser.php?page=1' class='popularcountry' id='".$maxcouns[3]."'>".$maxcoun1[3]."</a><br>";
}


function getPopularCity(){
    global $geocities;
    $cities=array();
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from travelimage where CityCode is not null";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            if(isset($cities[$row['CityCode']])){
                $cities[$row['CityCode']]++;
            }else{
                $cities[$row['CityCode']]=1;
            }
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
    $cities['ABC']=0;$cities['DEF']=0;$cities['GHI']=0;$cities['JKL']=0;$cities['MNO']=0;
    $maxcity=['ABC','DEF','GHI','JKL','MNO'];
    foreach($cities as $key=>$value){
        if($value>=$cities[$maxcity[0]]){
            $maxcity[4]=$maxcity[3];
            $maxcity[3]=$maxcity[2];
            $maxcity[2]=$maxcity[1];
            $maxcity[1]=$maxcity[0];
            $maxcity[0]=$key;
        }
        elseif($value>=$cities[$maxcity[1]]){
            $maxcity[4]=$maxcity[3];
            $maxcity[3]=$maxcity[2];
            $maxcity[2]=$maxcity[1];
            $maxcity[1]=$key;
        }
        elseif($value>=$cities[$maxcity[2]]){
            $maxcity[4]=$maxcity[3];
            $maxcity[3]=$maxcity[2];
            $maxcity[2]=$key;
        }
        elseif($value>=$cities[$maxcity[3]]){
            $maxcity[4]=$maxcity[3];
            $maxcity[3]=$key;
        }
        elseif($value>=$cities[$maxcity[4]]){
            $maxcity[4]=$key;
        }
    }
    $maxcity1=array();
    for($i=0;$i<5;$i++){
        $maxcity1[$i]=$geocities[$maxcity[$i]];
    }
    echo "<p><img src='../../img/others/disc.png' name='disc3'>热门城市快速浏览</p>";
    echo "<a href='browser.php?page=1' class='popularcity' id='".$maxcity[0]."'>".$maxcity1[0]."</a><br>";
    echo "<a href='browser.php?page=1' class='popularcity' id='".$maxcity[1]."'>".$maxcity1[1]."</a><br>";
    echo "<a href='browser.php?page=1' class='popularcity' id='".$maxcity[2]."'>".$maxcity1[2]."</a><br>";
    echo "<a href='browser.php?page=1' class='popularcity' id='".$maxcity[3]."'>".$maxcity1[3]."</a><br>";
    echo "<a href='browser.php?page=1' class='popularcity' id='".$maxcity[4]."'>".$maxcity1[4]."</a><br>";
}

function getPopularTheme(){
    $themes=array();
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="select * from travelimage where Content is not null";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            if(isset($themes[$row['Content']])){
                $themes[$row['Content']]++;
            }else{
                $themes[$row['Content']]=1;
            }
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
    $themes['ABC']=0;$themes['DEF']=0;$themes['GHI']=0;
    $maxtheme=['ABC','DEF','GHI'];
    foreach($themes as $key=>$value){
        if($value>=$themes[$maxtheme[0]]){
            $maxtheme[2]=$maxtheme[1];
            $maxtheme[1]=$maxtheme[0];
            $maxtheme[0]=$key;
        }
        elseif($value>=$themes[$maxtheme[1]]){
            $maxtheme[2]=$maxtheme[1];
            $maxtheme[1]=$key;
        }
        elseif($value>=$themes[$maxtheme[2]]){
            $maxtheme[2]=$key;
        }
    }
    echo "<p><img src='../../img/others/disc.png' name='disc4'>热门主题快速浏览</p>";
    echo "<a href='browser.php?page=1' class='populartheme' id='".$maxtheme[0]."'>".$maxtheme[0]."</a><br>";
    echo "<a href='browser.php?page=1' class='populartheme' id='".$maxtheme[1]."'>".$maxtheme[1]."</a><br>";
    echo "<a href='browser.php?page=1' class='populartheme' id='".$maxtheme[2]."'>".$maxtheme[2]."</a><br>";
}

function showpic($pics){
    //展示图片。
    global $picpath;
    if(!isset($_GET['page'])){
        //用url后缀中的page变量表示当前的页数。如果无这个变量则默认视为第一页
        echo "<script>window.location.href='browser.php?page=1';</script>";
    }
    if($pics=="nothing"){
        // $pics为输入的参数，是字符串形式串联起来的一系列图片id，在数字之间用x分隔。
        //进行搜索时改变cookie中的browser变量，展示时将其作为pics参数传入。如果搜索没搜到则传入nothing
        echo "<p id='nothing'>没有找到相关图片</p>";
    }
    else{
        $picarray=explode("x",$pics);
        $patharray=array();
        for($i=0;$i<sizeof($picarray);$i++){
            $patharray[$i]=$picpath[$picarray[$i]];
        }
        $num=sizeof($picarray);
        if($num>5*6*4){
            //若页数超过5页则只展示5页
            $num=5*6*4;
            $pages=5;
        }
        $pages=(int)(($num-1)/(6*4))+1;
        if($_GET['page']==$pages){
            //展示时分两种情况：当前不是最后一页和是最后一页
            echo "<table>";
            for($i=($pages-1)*24;$i<$num;$i++){
                if(($i-($pages-1)*24)%4==0){
                    echo "<tr>";
                }
                echo "<td>";
                echo "<div class= 'pic'><img src='../../img/normal/medium/".$patharray[$i]."'class='pic' onclick=\"window.location.href='details.php?id=".$picarray[$i]."'\"></div>";
                echo "</td>";
                if(($i-($pages-1)*24)%4==3){
                    echo "</tr>";
                }
            }
            echo "</table>";
        }else{
            echo "<table>";
            for($i=($_GET['page']-1)*24;$i<$_GET['page']*24;$i++){
                if(($i-($pages-1)*24)%4==0){
                    echo "<tr>";
                }
                echo "<td>";
                echo "<div class= 'pic'><img src='../../img/normal/medium/".$patharray[$i]."'class='pic' onclick=\"window.location.href='details.php?id=".$picarray[$i]."'\"></div>";
                echo "</td>";
                if(($i-($pages-1)*24)%4==3){
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
        echo "<div id= 'page'>";
        //打印页码，并加链接
        if($_GET['page']==1){
            $last=1;
        }else{
            $last=$_GET['page']-1;
        }
        if($_GET['page']==$pages){
            $next=$pages;
        }else{
            $next=$_GET['page']+1;
        }
        echo "<a href='browser.php?page=".$last."' class= 'page' id='lastpage'>&lt</a>";
        for($i=0;$i<$pages;$i++){
            echo "<a href='browser.php?page=".($i+1)."' class='pagenum' id='page".($i+1)."'>".($i+1)."</a>";
        }
        echo "<a href='browser.php?page=".$next."' class= 'page' id='nextpage'>&gt</a>";
        echo "</div>";
    }
}

?>
<html>
    <head>
        <title>浏览页</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../../reset.css" type="text/css">
        <link rel="stylesheet" href="../css/browser-common.css" type="text/css">
        <link media="(max-width:1360px) and (min-width:600px)" rel="stylesheet" href="../css/browser-middle.css" type="text/css">
        <link media="(max-width:1280px) and (min-width:600px)" rel="stylesheet" href="../css/browser-small.css" type="text/css">
        <link media="(max-width:600px)" rel="stylesheet" href="../css/browser-mobile.css" type="text/css">
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
        <!--此为侧边栏展开图标，仅在移动端显示，移动端影藏侧边栏，点击图标打开-->
        <img src="../../img/others/open.png" class="open">
        <aside>
            <ul class="aside">
                <li class="search">
                    <p><img src="../../img/others/disc.png" name="disc1">图片标题浏览</p>
                    <form action='' method='post' role='form' id='form'>
                        <?php
                        echo "<input type='text' class='title' placeholder='search ".sizeof($picpath)." pics' name='titlesearch' autofocus>";
                        ?>
                        <input type="submit" value="" class="search">
                    </form>
                    <?php
                    //实现模糊查询，搜索a得到%a%的结果，然后还实现a b的搜索能够找到%a%b%
                    if ($_SERVER["REQUEST_METHOD"] == "POST"){
                        if(isset($_POST['titlesearch'])){
                        $IDs="";
                        $search=$_POST['titlesearch'];
                        $keywords=preg_split("/[\s,]+/",$search); //支持通过空格或者，分隔的搜索
                        $sql="select * from travelimage where title is not null ";
                        foreach($keywords as $keyword){
                            $sql.="and title like '%".$keyword."%'";
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
                            $IDs=rtrim($IDs,"x");//去掉最右端多余的x
                        }else{
                            $IDs='nothing';
                        }
                        //通过字符串实现php与js脚本间的联系，从而实现功能
                        echo "<script>
                            let xhr=new XMLHttpRequest();
                            let url='../php/browser1.php?type=select&value=".$IDs."';
                            xhr.open('get',url,false);
                            xhr.send(null);
                            window.location.href='browser.php?page=1'</script>";
                    }
                    }
                    ?>
                </li>
                <hr>
                <li>
                   <?php getPopularCountry();?>
                </li>
                <hr>
                <li>
                    <?php getPopularCity();?>
                </li>
                <hr>
                <li>
                    <?php getPopularTheme();?>
                </li>
            </ul>
        </aside>
        <main>
            <section>
                <form action='' method='post' role='form' id='form'>
                    <select id="topic" class="filter" name="select1">
                        <option value="">--</option>
                        <option value="city">city</option>
                        <option value="building">buiding</option>
                        <option value="scenery">scenery</option>
                        <option value="wonder">wonder</option>
                        <option value="people">people</option>
                        <option value="animal">animal</option>
                        <option value="other">other</option>
                    </select>
                    <select id="country" class="filter" name="select2">
                        <option selected value="">请选择</option>   
                        <?php
                            foreach($geocountry as $key=>$value){
                                echo "<option value='".$key."'>".$value."</option>";
                            }
                        ?>
                    </select>
                    
                    <select id="cities" class="filter" name="select3">
                        <option value=''>--</option>
                        <?php
                        //实现二级筛选
                        $str="";
                        //将二维数组表示为字符串:country1?city1%cityname1&city2%cityname2#country2......
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
                        
                        //因为要将php从mysql数据库中读取到并编写成的字符串在js中使用，所以用将脚本放在echo里面。
                        echo "<script>
                            var str='".$str."';
                            var map1=new Map();
                            var arr1=str.split('#');
                            for(let i=0;i<arr1.length;i++){
                                map1=map1.set(arr1[i].split('?')[0],arr1[i].split('?')[1]);
                            }

                            var cities=document.getElementById('cities');
                            var citystr=\"<option value=''>--</option>\";
                            document.getElementById('country').onchange=function(){ 
                            var coun=document.getElementById('country').value;
                            if(coun==''){
                                cities.innerHTML=citystr;
                            }
                            var getstr=map1.get(coun);
                            var arr2=getstr.split('&');
                            var map2=new Map();
                            for(a of arr2){
                                map2=map2.set(a.split('%')[0],a.split('%')[1]);
                            }
                            for(let key of map2.keys()){
                                citystr+=\"<option value='\"+key+\"'>\"+map2.get(key)+\"</option>\";
                            }
                            cities.innerHTML=citystr;
                        }</script>"
                        ?>
                    </select>

                    <input type="submit" class="filter" value="筛选">
                    <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST"){
                            //提交表单，实现筛选
                            $IDs="";
                            $theme=($_POST['select1']=="")?"ImageID is not null":"Content='".$_POST['select1']."'";//判断下拉框是否选中某值
                            $country=($_POST['select2']=="")?"":" and CountryCodeISO='".$_POST['select2']."'";
                            $city=($_POST['select3']=="")?"":" and CityCode='".$_POST['select3']."'";
                            try{
                                $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $sql="select * from travelimage where ".$theme.$country.$city;
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
                            }else{
                                $IDs='nothing';
                            }
                            
                            echo "<script>
                            let xhr=new XMLHttpRequest();
                            let url='../php/browser1.php?type=select&value=".$IDs."';
                            xhr.open('get',url,false);
                            xhr.send(null);
                            window.location.href='browser.php?page=1'</script>";
                        }
                    ?>
                </form>
                <p id="tip">以下为浏览结果</p>
                <?php
                    if(isset($_COOKIE['browser'])){
                        showpic($_COOKIE['browser']);
                    }
                    else{
                        //刚进页面，没有搜索时用随机图片填充页面
                        $ranstr='';
                        $all=range(1,count($picpath));
                        $ranarr=array_rand($all,24);
                        for($i=0;$i<24;$i++){
                            $ranstr.=($ranarr[$i]+1).'x';
                        }
                        $ranstr=rtrim($ranstr,"x");
                        showpic($ranstr);    
                    }
                ?>
                 <?php ?>
                <p><br></p>
            </section>
        </main>
        <footer>
            文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com
        </footer>
        <script src="../javascript/browser.js"></script>
    </body>
</html>