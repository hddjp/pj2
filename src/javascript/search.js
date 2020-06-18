var pages=document.getElementsByClassName('pagenum');
var u=window.location.href;
var num=u.charAt(u.length-1);
for(page of pages){
    p=page.innerText;
    if(num==p){
        page.style.fontSize="25px";
    }
}


var navs=document.getElementsByClassName('navigation');
for(let nav of navs){
    nav.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/search1.php?type=cleancookie";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}
var boxs=document.getElementsByClassName("box");
for(let box of boxs){
    box.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/search1.php?type=cleancookie";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}

var logout=document.getElementById("logout");
if(logout instanceof Object){
    logout.addEventListener("click",function(){
        var xhr=new XMLHttpRequest();
        var url="../php/search1.php?type=logout";
        xhr.open("get",url);
        xhr.send(null);
        document.location.href=document.location.href;
        alert("账户已登出");
    })
}