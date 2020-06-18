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

var pages=document.getElementsByClassName('pagenum');
var u=window.location.href;
var num=u.charAt(u.length-1);
if(!(num>0&&num<=9)){
    num=1;
}
for(page of pages){
    p=page.innerText;
    if(num==p){
        page.style.fontSize="25px";
    }
}

var buttons=document.getElementsByClassName("butt");
for(let button of buttons){
    button.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/favorates1.php?imgid="+button.id;
        xhr.open("get",url,false);
        xhr.send(null);
        alert("已将该照片从您的收藏夹中移除！")
        window.location.href=window.location.href;
    })
}
