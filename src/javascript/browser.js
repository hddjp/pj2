var popcouns=document.getElementsByClassName('popularcountry');
for(let popcoun of popcouns){
    popcoun.addEventListener('click',function(){
        let xhr=new XMLHttpRequest();
        let url="../php/browser1.php?type=popcountry&name="+popcoun.id;
        xhr.open("get",url,false);
        xhr.send(null);
    })
}

var popcities=document.getElementsByClassName('popularcity');
for(let popcity of popcities){
    popcity.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/browser1.php?type=popcity&name="+popcity.id;
        xhr.open("get",url,false);
        xhr.send(null);
    })
}

var popthemes=document.getElementsByClassName('populartheme');
for(let poptheme of popthemes){
    poptheme.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/browser1.php?type=poptheme&name="+poptheme.id;
        xhr.open("get",url,false);
        xhr.send(null);
    })
}

var navs=document.getElementsByClassName('navigation');
for(let nav of navs){
    nav.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/browser1.php?type=cleancookie";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}
var boxs=document.getElementsByClassName("box");
for(let box of boxs){
    box.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/browser1.php?type=cleancookie";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}

var logout=document.getElementById("logout");
if(logout instanceof Object){
    logout.addEventListener("click",function(){
        var xhr=new XMLHttpRequest();
        var url="../php/browser1.php?type=logout";
        xhr.open("get",url);
        xhr.send(null);
        document.location.href=document.location.href;
        alert("账户已登出");
    })
}

var pages=document.getElementsByClassName('pagenum');
var u=window.location.href;
var num=u.charAt(u.length-1);
for(page of pages){
    //将当前所在页面字体变大
    p=page.id.charAt(page.id.length-1);
    if(num==p){
        page.style.fontSize="25px";
    }
}