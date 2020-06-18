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

var favorate=document.getElementsByClassName("favor")[0];
if(isfavored){
    favorate.style.width="120px";
    favorate.childNodes[1].nodeValue="取消收藏";
    favorate.style.fontSize='16px';
    favorate.style.letterSpacing="0.2em";
}
favorate.addEventListener("click",function(){
    if(login){
        let xhr=new XMLHttpRequest();
        let url="../php/details1.php?con="+isfavored+"&id="+id;
        xhr.open("get",url);
        xhr.send(null);
        window.location.href=window.location.href;
    }else{
        alert('请先登录！');
    }
})

//从详情页面跳转到其他页面时清空cookie中的browser属性和search属性。
//目的是实现从搜索页或浏览页跳转到详情页时，点击后退键可以回到刚才页面并显示刚才搜索得到的值，所以不直接在这两个页面的图片上添加删除cookie的监听器
var navs=document.getElementsByClassName('navigation');
for(let nav of navs){
    nav.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/browser1.php?type=cleancookie";
        xhr.open("get",url,false);
        xhr.send(null);
        xhr=new XMLHttpRequest();
        url="../php/search1.php?type=cleancookie";
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
        xhr=new XMLHttpRequest();
        url="../php/search1.php?type=cleancookie";
        xhr.open("get",url,false);
        xhr.send(null);
    })
}