<!DOCTYPE html>

<?php require_once("../../config.php");

function translate($str){
    //因为url中特殊符号的特殊含义所以需要将其转义后写入，而读取也需反向转义
    $str=str_replace("%","%25",$str);
    $str=str_replace("+","%2B",$str);
    $str=str_replace(" ","%20",$str);
    $str=str_replace("/","%2F",$str);
    $str=str_replace("?","%3F",$str);
    $str=str_replace("/","%2F",$str);
    $str=str_replace("#","%23",$str);
    $str=str_replace("&","%26",$str);
    $str=str_replace("=","%3D",$str);
    return $str;
}
function retranslate($str){
    $str=str_replace("%25","%",$str);
    $str=str_replace("%2B","+",$str);
    $str=str_replace("%20"," ",$str);
    $str=str_replace("%2F","/",$str);
    $str=str_replace("%3F","?",$str);
    $str=str_replace("%2F","/",$str);
    $str=str_replace("%23","#",$str);
    $str=str_replace("%26","&",$str);
    $str=str_replace("%3D","=",$str);
    return $str;
}

?>
<html>
    <head>
        <title>注册</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../../reset.css" type="text/css">
        <link rel="stylesheet" href="../css/register-common.css" type="text/css">
        <link media="(max-width:600px)" rel="stylesheet" href="../css/register-mobile.css" type="text/css">
    </head>
    <body>
        <main>
            <h1>注册您的新账号</h1>
            <form  action='' method='post' role='form' id='form'>
                <p class="contents">
                    <img src="../../img/others/name.png" class="name"> 
                    <?php
                    if(isset($_GET['name'])){
                        //当用户输入非法信息时，将注册失败，提醒重写，并将注册信息放在url信息中返回，以便在原来基础上修改
                        echo "<input type='text' class='name' placeholder='用户名' name='UserName' value='".retranslate($_GET['name'])."'>";
                    } else{
                        echo "<input type='text' class='name' placeholder='用户名' name='UserName'>";
                    }
                    ?>
                   
                </p>
                <p class="contents">
                    <img src="../../img/others/email.png" class="email">
                    <?php
                    if(isset($_GET['email'])){
                        echo "<input type='email' class='email' placeholder='邮箱地址' name='Email' value='".retranslate($_GET['email'])."'>";
                    } else{
                        echo "<input type='email' class='email' placeholder='邮箱地址' name='Email'>";
                    }
                    ?>
                </p>
                <p class="contents">
                    <img src="../../img/others/password.png" class="password">
                    <?php
                    if(isset($_GET['password'])){
                        echo "<input type='password' class='password' placeholder='密码' name='Pass' value='".retranslate($_GET['password'])."'>";
                    }else{
                        echo "<input type='password' class='password' placeholder='密码' name='Pass'>";
                    }
                    ?>
                </p>
                <p class="contents">
                    <img src="../../img/others/password.png" class="password">
                    <?php
                    if(isset($_GET['confirm'])){
                        echo "<input type='password' class='confirm' placeholder='密码确认' name='confirm' value='".retranslate($_GET['confirm'])."'>";
                    } else{
                        echo "<input type='password' class='confirm' placeholder='密码确认' name='confirm'>";
                    }
                    ?>
                </p>
                <input type="submit" id="register" value="注册">
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $users=array();
                try{
                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql="select * from traveluser";
                    $result=$pdo->query($sql);
                    while($row=$result->fetch()){
                        $users[]=$row['UserName'];
                    }
                }
                catch (PDOException $e) {
                    die( $e->getMessage() );
                } 
                $repeat=false;
                if(isset($_POST['UserName'])){
                    //判断用户名有无重复
                    foreach($users as $user){
                        if(strcmp($user,$_POST['UserName'])==0){
                            //无视大小写比较
                            $repeat=true;
                        }
                    }
                }

                //判断密码强弱
                $score=0;
                if(isset($_POST['Pass'])){
                    $str=$_POST['Pass'];
                }else{
                    $str='';
                }
                if(preg_match("/[0-9]+/",$str)){
                    $score ++;
                }
                if(preg_match("/[0-9]{3,}/",$str)){
                    $score ++;
                }
                if(preg_match("/[a-z]+/",$str)){
                    $score ++;
                }
                if(preg_match("/[a-z]{3,}/",$str)){
                    $score ++;
                }
                if(preg_match("/[A-Z]+/",$str)){
                    $score ++;
                }
                if(preg_match("/[A-Z]{3,}/",$str)){
                    $score ++;
                }
                if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/",$str)){
                    $score += 2;
                }
                if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]{3,}/",$str)){
                    $score ++ ;
                }
                if(strlen($str) >= 10){
                    $score ++;
                }

                if(strlen($_POST['UserName'])<5){
                    //用户名应当在5-20位之间，仅包括字母、数字和._
                    echo "<script>alert('你的用户名太短了！请重新输入');
                    window.location.href='register.php?name=".translate($_POST['UserName'])."&email=".translate($_POST['Email'])."&password=".translate($_POST['Pass'])."&confirm=".translate($_POST['confirm'])."';</script>";
                }elseif(strlen($_POST['UserName'])>20){
                    echo "<script>alert('你的用户名太长了！请重新输入');
                    window.location.href='register.php?name=".translate($_POST['UserName'])."&email=".translate($_POST['Email'])."&password=".translate($_POST['Pass'])."&confirm=".translate($_POST['confirm'])."';</script>";
                }elseif(!preg_match("/^[a-zA-Z0-9_\.]+$/",$_POST['UserName'])){
                    echo "<script>alert('用户名不能含有除字母、数字、.、_以外的字符！请重新输入');
                    window.location.href='register.php?name=".translate($_POST['UserName'])."&email=".translate($_POST['Email'])."&password=".translate($_POST['Pass'])."&confirm=".translate($_POST['confirm'])."';</script>";
                }elseif($repeat){
                    echo "<script>alert('用户名重复，请重新输入');
                    window.location.href='register.php?name=".translate($_POST['UserName'])."&email=".translate($_POST['Email'])."&password=".translate($_POST['Pass'])."&confirm=".translate($_POST['confirm'])."';</script>";
                }elseif($_POST['Pass']!=$_POST['confirm']){
                    echo "<script>alert('两次输入密码不一致，请重新输入');
                    window.location.href='register.php?name=".translate($_POST['UserName'])."&email=".translate($_POST['Email'])."&password=".translate($_POST['Pass'])."&confirm=".translate($_POST['confirm'])."';</script>";
                }elseif(!preg_match("/^[a-zA-z0-9]+([-_\.]*[a-zA-z0-9]+)*@([a-zA-z0-9]+[-_\.]?)*[a-zA-z0-9]+\.[a-zA-Z]{2,4}$/",$_POST['Email'])){
                    echo "<script>alert('邮箱格式错误，请重新输入');
                    window.location.href='register.php?name=".translate($_POST['UserName'])."&email=".translate($_POST['Email'])."&password=".translate($_POST['Pass'])."&confirm=".translate($_POST['confirm'])."';</script>";
                }elseif($score<4){
                    echo "<script>alert('密码强度过低，请重新输入。采取字母、数字、符号之间的组合能增加密码强度');
                    window.location.href='register.php?name=".translate($_POST['UserName'])."&email=".translate($_POST['Email'])."&password=".translate($_POST['Pass'])."&confirm=".translate($_POST['confirm'])."';</script>";
                }else{
                    $salt=base64_encode(openssl_random_pseudo_bytes(32));
                    //哈希加盐
                    $hash=sha1($_POST['Pass'].$salt);
                    try{
                        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sql="insert into traveluser (Email,UserName,HashValue,Salt) values ('".$_POST['Email']."','".$_POST['UserName']."','".$hash."','".$salt."')";
                        $pdo->exec($sql);
                    }   
                    catch (PDOException $e) {
                        die( $e->getMessage() );
                    }
                    echo "<script>window.location.href='signin.php';</script>"; 
                }
            }
            ?>
        </main>
        <footer>
            文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com
        </footer>
    </body>
</html>