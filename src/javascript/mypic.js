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

//修改按钮直接跳转到上传页面并在url中的id属性表示当前的图片。删除按钮通过ajax实现删除
var modifies=document.getElementsByClassName("modify");
for(let modify of modifies){
    modify.addEventListener("click",function(){
        window.location.href="upload.php?id="+modify.name;
    })
}

var deletors=document.getElementsByClassName("delete");
for(let deletor of deletors){
    deletor.addEventListener("click",function(){
        let xhr=new XMLHttpRequest();
        let url="../php/mypic1.php?imgid="+deletor.name;
        xhr.open("get",url,false);
        xhr.send(null);
        alert("删除成功！");
        window.location.href=window.location.href;
    })
}