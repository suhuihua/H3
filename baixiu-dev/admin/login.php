<?php 
//载入配置文件
require_once '../config.php';

//调入session_start(),放置访问标记
session_start();

function login(){
  //1.接收校验
  //2.持久化
  //3.响应
  if(empty($_POST['email'])){
    $GLOBALS['message'] = '请填写邮箱';
    return ;
  }

  if(empty($_POST['password'])){
    $GLOBALS['message'] = '请填写密码';
    return ;
  }

  $email = $_POST['email'];
  $password = $_POST['password'];

//以上是表单校验。接下来进行数据库校验

//以下进行数据库校验

  // if($email!=='su@qq.com'){
  //   $GLOBALS['message'] = '邮箱与密码不匹配';
  //   return;
  // }

  // if($password!=='123'){
  //   $GLOBALS['message'] = '邮箱与密码不匹配';
  //   return;
  // }
$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if(!$conn){
  exit('<h1>数据库连接失败</h1>');
}
//防止读取的数据出现乱码???
mysqli_query($conn,"set names 'utf8' ");  
mysqli_query($conn,"set character_set_client=utf8");   
mysqli_query($conn,"set character_set_results=utf8");
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}' limit 1;");
if(!$query){
  $GLOBALS['message'] = '登录失败，请重试';
  return;
}

$user = mysqli_fetch_assoc($query);
if(!$user){
    $GLOBALS['message'] = '邮箱与密码不匹配';
    return;
}

if($user['password']!==$password){
    $GLOBALS['message'] = '邮箱与密码不匹配';
    return;
}

//存一个表示
// $_SESSION['is_login_in'] = true;
  $_SESSION['current_login_user']= $user;

//账号密码一致，进行下一步
header('Location: /admin/');

}


if($_SERVER['REQUEST_METHOD']==='POST'){
  login();
}
//退出登录
if($_SERVER['REQUEST_METHOD']==='GET'&&isset($_GET['action'])&&$_GET['action']==='logout'){
  unset($_SESSION['current_login_user']);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap<?php echo isset($message)?' shake animated':'';?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate autocomplete=off >
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong> 用户名或密码错误！
      </div> -->
      <?php if(isset($message)): ?>
        <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $message; ?>
        </div>
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo !empty($_POST['email'])?$_POST['email']:''; ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script>
    $(function ($) {
      //传入$，单独作用域
      //确保加载完再执行
      //目标：用户输入邮箱，显示头像
      //失去焦点之后,并且填写了邮箱
      var emailFormat =/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/

      $("#email").on('blur',function (){
        // console.log($(this).val())
        var value = $(this).val()
        if(!value|| !emailFormat.test(value)) return;
        //邮箱达标，获取头像地址
        $.get('/admin/api/avatar.php',{email:value},function(res){
          //res->这个邮箱头像地址
          if(!res) return;
          $('.avatar').fadeOut(function(){
            $(this).on('load',function(){
              $(this).fadeIn();
            }).attr('src',res);
          });
        });

      });
    });
  </script>
</body>
</html>
