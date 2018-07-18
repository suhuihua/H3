<?php 
require_once '../functions.php';

$current_user = baixiu_get_current_user();

$category=baixiu_fetch_all('select * from categories;');

function add_posts(){
  if(empty($_POST['title'])){
    $GLOBALS['message'] ='请输入标题';
    return;
  }


  if(empty($_POST['content'])){
    $GLOBALS['message'] ='请输入文章内容';
    return;
  }


  if(empty($_POST['slug'])){
    $GLOBALS['message'] ='请输入别名';
    return;
  }


  if(empty($_POST['category'])){
    $GLOBALS['message'] ='请选择文章所属分类';
    return;
  }


  if(empty($_POST['status'])){
    $GLOBALS['message'] ='缺少状态';
    return;
  }


  if(empty($_POST['created'])){
    $GLOBALS['message'] ='缺少发布时间';
    return;
  }

  if($_FILES['feature']['error']>0){
    $GLOBALS['message'] ='特色图像上传失败1';
    return;
  }
  $feature = $_FILES['feature'];
  $allowed = ['png','jpg','jpeg','gif'];
  $allowedType = ['image/gif','image/jpeg','image/jpg','image/pjpeg','image/x-png','image/png'];
  $temp = explode('.',$feature['name']);
  $extension = strtolower(end($temp));
  // var_dump(in_array($feature['type'], $allowedType));
  // var_dump(in_array($extension,$allowed));
  // var_dump( ((int)$feature['size'])<20*1024*1024);
  if(!(in_array($feature['type'], $allowedType)&&in_array($extension,$allowed)&&$feature['size']<20*1024*1024)){
    $GLOBALS['message'] ='特色图像上传失败2';
    return;
  }
  $url = '../static/uploads/'.uniqid().'.'.$extension;
  move_uploaded_file($feature['tmp_name'], $url);
  $url = substr($url, 2);



  $title = $_POST['title'];
  $content = $_POST['content'];
  $slug = $_POST['slug'];
  $category = $_POST['category'];
  $status = $_POST['status'];
  $created = implode(" ", explode("T", $_POST['created'])).':00';
  $user_id = $_POST['user'];
  $res = baixiu_execute("insert into posts 
    (slug,title,feature,created,content,status,user_id,category_id) 
    values 
    ('{$slug}','{$title}','{$url}','{$created}','{$content}','{$status}','{$user_id}','{$category}');");
  if($res == 1){$GLOBALS['chenggong'] = '保存成功！';}
  }

if($_SERVER['REQUEST_METHOD']==='POST'){
  add_posts();
}
 ?>






<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
   <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <?php if (isset($message)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $message; ?>
        </div>
        <?php elseif(isset($chenggong)): ?>
         <div class="alert alert-success">
          <strong>OK!</strong><?php echo $chenggong; ?>
        </div> 
      <?php endif ?>      
      <form class="row" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="user" value="<?php echo $current_user['id']; ?>">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题" value="<?php echo isset($baixiu_title)? $baixiu_title:''; ?>">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <!-- <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea> -->
            <script id="content" type="text/plain" name="content"></script>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach ($category as $item): ?>
                <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local" readonly="readonly">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php $gaoliang='post-add'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/ueditor1_4_3_3-utf8-php/ueditor.config.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/ueditor1_4_3_3-utf8-php/ueditor.all.js"></script>
  <script type="text/javascript">
    var ue = UE.getEditor('content',{
      initialFrameHeight:320});
 </script>
 <script>
    $(function($){
      //此处处理发布时间的时间显示,取本机时间
      var $time = $("#created");
      function time() {
        var date = new Date();
        var n = date.getFullYear();
        var y = (date.getMonth()+1)<10?"0"+(date.getMonth()+1):(date.getMonth()+1);
        var t = date.getDate()<10?"0"+date.getDate():date.getDate();
        var h = date.getHours()<10?"0"+date.getHours():date.getHours();
        var m = date.getMinutes()<10?"0"+date.getMinutes():date.getMinutes();
        var s = date.getSeconds()<10?"0"+date.getSeconds():date.getSeconds();
        //input控件datetime-local时间格式为2018-07-15T01:27
        $time.val(n+"-"+y+"-"+t+"T"+h+":"+m);
      }
      time();
      setInterval(time, 60000);


    });



 </script>
  <script>NProgress.done()</script>
</body>
</html>
