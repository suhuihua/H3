<?php 


require_once '../functions.php';


baixiu_get_current_user();

if(!empty($_GET['id'])){
  //一旦提交get请求，修改数据
  $current_edit = baixiu_fetch_one("select * from categories where id = {$_GET['id']}");
}

function edit_category() {
  global $current_edit;
  //接收保存
  $id = $current_edit['id'];
  //此处修改$current_edit['name']与$current_edit['slug']的值，因为查询先于修改，如果不修改这两个值，下面表单呈现的则为旧数据，显示的却是最新数据
  //$name = empty($_POST['name'])?$current_edit['name']:$_POST['name'];
  //$slug = empty($_POST['slug'])?$current_edit['slug']:$_POST['slug'];
  $current_edit['name'] = $name = empty($_POST['name'])?$current_edit['name']:$_POST['name'];
  $current_edit['slug'] = $slug = empty($_POST['slug'])?$current_edit['slug']:$_POST['slug'];
  $rows = baixiu_execute("update categories set slug = '{$slug}' ,name = '{$name}' where id = '{$id}' ;");
  $GLOBALS['success'] = $rows>0;
  $GLOBALS['message'] = ($rows<=0 ?'更新失败!':'更新成功!');
}

function add_category(){
  if(empty($_POST['name'])||empty($_POST['slug'])){
    $GLOBALS['message']='请填写完整表单!';
    return;
  }
  //接收保存
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $rows = baixiu_execute("insert into categories values (null,'{$slug}','{$name}');");
  $GLOBALS['success'] = $rows>0;
  $GLOBALS['message'] = ($rows<=0 ?'添加失败!':'添加成功!');
}

//先新增后查询
if($_SERVER['REQUEST_METHOD']==='POST'){
  //一旦提交post请求，添加数据
  if(empty($_GET['id'])){
    add_category();
  } else {
    edit_category();
  }
}



//查询数据库内容
$categories = baixiu_fetch_all('select * from categories;');




 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <?php if (isset($message)): ?>
        <?php if (isset($success)&&$success=='ture'): ?>
            <div class="alert alert-success">
              <strong>OK！</strong><?php echo $message; ?>
            </div>
          <?php else: ?>
            <div class="alert alert-danger">
              <strong>错误！</strong><?php echo $message; ?>
            </div>
        <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <?php if (isset($current_edit)): ?>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $current_edit['id']; ?>" method="post" autocomplete=off>
            <h2>编辑《 <?php echo $current_edit['name']; ?> 》</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo $current_edit['name']; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $current_edit['slug']; ?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
              <a class="btn btn-default" href="/admin/categories.php">取消</a>
            </div>
          </form>
            <?php else: ?>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete=off>
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
              
            </div>
          </form>
          <?php endif ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="btn_delete" class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox" id="all"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as  $item): ?>
                <tr>
                <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id'] ?>" name="sub"></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['slug']; ?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?id=<?php echo $item['id'] ?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/category-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php $gaoliang='categories'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function(){
      var $btnDelete = $('#btn_delete');
      var $all = $("#all");
      var $sub = $("input[name='sub']");
      var allCheckeds = [];
      $sub.on("change",function (){
        var flag = 0;
        var id = $(this).data('id');
        if($(this).prop('checked')){
          // allCheckeds.push(this.dataset['id']);
          // allCheckeds.push($(this).attr('data-id'));
          allCheckeds.push(id);
        } else {
          allCheckeds.splice(allCheckeds.indexOf(id),1);
        }
        allCheckeds.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();
        $btnDelete.attr('href','/admin/category-delete.php?id='+allCheckeds);
        $sub.each(function(i,item){
          if($(item).prop("checked")){
            flag++;
          }
        });
        $sub.length == flag ? $all.prop("checked",true) : $all.prop("checked",false);
      });

      $all.on("change",function (){
        allCheckeds = [];
        $sub.prop("checked",this.checked);
        if(this.checked){
          $sub.each(function(i,item){
            allCheckeds.push($(item).data('id'));
          });
        }
        allCheckeds.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();
        $btnDelete.attr('href','/admin/category-delete.php?id='+allCheckeds);
      });
    });
  </script>
  <!-- <script>
    $(function(){
      // var $tbodyCheckboxs = $('tbody input');
      var $tbodyCheckboxs = $("input[name='sub']");
      var $btnDelete = $('#btn_delete');
      var $all = $("#all");
      var $sub = $("input[name='sub']");
      var allCheckeds = [];
      $tbodyCheckboxs.on('change',function(){
        var id = $(this).data('id');
        if($(this).prop('checked')){
          // allCheckeds.push(this.dataset['id']);
          // allCheckeds.push($(this).attr('data-id'));
          allCheckeds.push(id);
        } else {
          allCheckeds.splice(allCheckeds.indexOf(id),1);
        }
        allCheckeds.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();
        $btnDelete.attr('href','/admin/category-delete.php?id='+allCheckeds);
        // $sub.length==$("input[name='sub']:checked").length? $all.prop("checked",true):$all.prop("checked",false);
      });
      $all.on('change',function(){
        $sub.prop("checked",this.checked);
        allCheckeds = [];
        $tbodyCheckboxs.change();
      })
    });
  </script> -->
  <!-- <script>
    $(function($){
      var $tbodyCheckboxs = $('tbody input');
      var $btnDelete = $('#btn_delete');
      $tbodyCheckboxs.on('change',function(){
        var flag = false;
        $tbodyCheckboxs.each(function(i,item){
          if($(item).prop('checked')){
            flag=true;
          }
        });

        flag ? $btnDelete.fadeIn() : $btnDelete.fadeOut();

      });
    });
  </script> -->
  <script>NProgress.done()</script>
</body>
</html>
