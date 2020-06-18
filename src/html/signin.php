<!DOCTYPE html>
<?php
require_once("../../config.php");

function validLogin(){
    //判断是否登录成功
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);  
    $sql="select * from traveluser where UserName='".$_POST['name']."'";  
    $statement = $pdo->prepare($sql);   
    $statement->execute();   
    if($statement->rowCount()==0){//没有用户
        return false;
    }else{
        $sql="select * from traveluser where UserName='".$_POST['name']."'";
        $result=$pdo->query($sql);
        while($row=$result->fetch()){
            $salt=$row['salt'];
            $hashvalue=$row['HashValue'];
        }
        //用户的密码加上用户的盐后哈希化，和数据库中保存值进行比对
        $hash=sha1($_POST['password'].$salt);
        if($hash==$hashvalue){
            return true;
        }else{
            return false;
        }
    }
}
?>
<html>
    <head>
        <title>登录</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="../../reset.css" type="text/css">
        <link rel="stylesheet" href="../css/signin-common.css" type="text/css">
        <link media="(max-width:600px)" rel="stylesheet" href="../css/signin-mobile.css" type="text/css">
    </head>
    <body>
        <main>
            <h1>账号密码登录</h1>
            <form action='' method='post' role='form' id='form'>
                <p class="contents">
                    <!--在输入框之前用图标，placeholder提示语-->
                    <img src="../../img/others/name.png" class="name"> 
                    <input type="text" class="name" placeholder="输入用户名" name="name">
                </p>
                <p class="contents">
                    <img src="../../img/others/password.png" class="password">
                    <input type="password" class="password" placeholder="输入密码" name="password">
                </p>
                <input type="submit" value="登录" id="signin">
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
                        if(validLogin()){  
                        $expiryTime = time()+60*60*24;  
                        setcookie("Username", $_POST['name'], $expiryTime,"/pj2");
                        echo "<script>alert('欢迎登录,'+'".$_POST['name']."');
                        window.location.href='../../index.php';</script>";
                    }
                        else{  
                            echo "<script>alert('用户名或者密码错误');</script>";  
                        }  
                    }
                ?>
                <p class="register">
                    <a href="register.php">注册新账号</a>
                </p>
            </form>
        </main>
        <footer>
            文明办网 文明上网 举报、纠纷处理及不良内容举报电话：110 | 举报邮箱：19302010036@fudan.com
        </footer>
    </body>
</html>