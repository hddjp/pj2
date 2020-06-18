//通过ajax来将信息传递到一些php文件中，从而达到改变cookie等效果。不同的url后缀实现不同功能
var refresh=document.getElementsByClassName("float")[0];
refresh.addEventListener("click",function(){
    var xhr=new XMLHttpRequest();
    var url="src/php/index1.php?con=1";
    xhr.open("get",url,false);
    xhr.send(null);
    document.location.href=document.location.href;
})

var logout=document.getElementById("logout");
if(logout instanceof Object){
    //添加监听器之前首先判断按钮是否存在
    logout.addEventListener("click",function(){
        var xhr=new XMLHttpRequest();
        var url="src/php/index1.php?con=2";
        xhr.open("get",url);
        xhr.send(null);
        document.location.href=document.location.href;
        alert("账户已登出")
    })
}

var navs=document.getElementsByClassName('navigation');
for(let nav of navs){
    //页面跳转时消除ranpic的cookie
    nav.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="src/php/index1.php?con=3";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}
var boxs=document.getElementsByClassName("box");
for(let box of boxs){
    box.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="src/php/index1.php?con=3";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}
var captions=document.getElementsByTagName("figcaption");
for(let caption of captions){
    caption.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="src/php/index1.php?con=3";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}
var pics=document.getElementsByClassName("pic");
for(let pic of pics){
    pic.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="src/php/index1.php?con=3";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}
