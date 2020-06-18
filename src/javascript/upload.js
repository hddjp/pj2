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

function changepic() {
    var reads = new FileReader();
    f = document.getElementById('file').files[0];
    reads.readAsDataURL(f);
    reads.onload = function(e) {
    document.getElementById('uploadimg').src = this.result;
    document.getElementById('uploadimg').className="after";
    };
}

//判断表单是否有空值，如有则阻止上传
var submit=document.getElementsByClassName('submit')[0];
var pic=document.getElementById('file');
var title=document.getElementsByName('title')[0];
var description=document.getElementsByName('description')[0];
var country=document.getElementsByName('country')[0];
var city=document.getElementsByName('city')[0];
submit.addEventListener("click",function(e){
    if(title.value==""||description.value==""||country.value==""||city.value==""){
        alert("有信息没有填入，请补充完整");
        e.preventDefault();
    }
})