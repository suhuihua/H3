<?php 

require_once '../functions.php';

baixiu_get_current_user();

//接收筛选参数,分类
$where= '1 = 1';
if(isset($_GET['category'])&&$_GET['category']!=='all'){
  $where .=' and posts.category_id = '.$_GET['category'];
  $cat=$_GET['category'];
}
//接收筛选参数,状态
if(isset($_GET['status'])&&$_GET['status']!=='all'){
  // $where .=' and posts.status = '."'{$_GET['status']}'";
  // 等价于下面这句
  $where .=" and posts.status = '{$_GET['status']}'";
  $sta=$_GET['status'];
}



//处理分页
$page = empty($_GET['page'])?1:(int)$_GET['page'];
$size = 20;

//最大页数 $total_pages=ceil($total_count/$size)
$total_count = (int)baixiu_fetch_one("select 
count(1) as num
from posts 
inner join users on posts.user_id = users.id 
inner join categories on posts.category_id = categories.id
where {$where};")['num'];
$total_pages=(int)ceil($total_count/$size);
if($page>$total_pages) $page=$total_pages;
if($page<1) $page=1;
//计算越过多少条数据
$skip = ($page-1)*$size;

//获取全部数据,包含状态的，如果无状态，则where处为1=1，不起作用
$posts = baixiu_fetch_all("select 
posts.id,
posts.title,
posts.created,
posts.status,
users.nickname as 'user_name', 
categories.name as 'category_name' 
from posts 
inner join users on posts.user_id = users.id 
inner join categories on posts.category_id = categories.id
where {$where}
order by posts.created desc
limit {$skip},{$size};");

//获取文章分类的数据，显示在筛选上面，其中回收站等状态只有几个，已写死
$categories = baixiu_fetch_all('select * from categories;');


//分页页码
$visiables = 5;//页码个数
$region = ($visiables-1)/2;//左右区间
$begin = $page - $region;//开始页码
$end = $begin + $visiables;//结束页码+1



//当begin为1，不可能显示负数，特殊处理一下
if($begin<1) {
  $begin=1;
  $end = $begin + $visiables;
}
//当总页数小于$visiables时,$end的值会大于页数，不合理，特殊处理
if($end>$total_pages+1){
  $end=$total_pages+1;
  $begin=$end-$visiables;
  if($begin<1) {
  $begin=1;
}
}






function convert_status ($status){
  $dict= array(
    'published'=>"已发布",
    'drafted'=>"草稿",
    'trashed'=>"回收站"
  );

  return isset($dict[$status])?$dict[$status]:'未知';
}

function convert_date($created){
    $timestamp = strtotime($created);
    return date('Y年m月d日<b\r>H:i',$timestamp);
}

// function get_category($category_id){
//   return baixiu_fetch_one("select name from categories where id = {$category_id}")['name'];
// }

// function get_user($user_id){
//   return baixiu_fetch_one("select nickname from users where id = {$user_id}")['nickname'];
// }


 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="btn_delete">批量删除</a>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id']; ?>"<?php echo isset($cat)&&$cat==$item['id']?' selected':''; ?>><?php echo $item['name']; ?></option>
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="published"<?php echo isset($sta)&&$sta=='published'?' selected':''; ?>>已发布</option>
            <option value="drafted"<?php echo isset($sta)&&$sta=='drafted'?' selected':''; ?>>草稿</option>
            <option value="trashed"<?php echo isset($sta)&&$sta=='trashed'?' selected':''; ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <?php if ($page!=1): ?>
            <li><a href="?page=<?php echo $page-1; ?><?php echo isset($cat)?"&category={$cat}":''; ?><?php echo isset($sta)?"&status={$sta}":''; ?>">上一页</a></li>
          <?php endif ?>
          <?php for($i=$begin; $i<$end; $i++): ?>
              <li <?php if($i==$page) echo "class="."active"; ?>><a href="?page=<?php echo $i; ?><?php echo isset($cat)?"&category={$cat}":''; ?><?php echo isset($sta)?"&status={$sta}":''; ?>"><?php echo $i; ?></a></li>
          <?php endfor ?>
          <?php if ($page<$total_pages&&$total_pages>1): ?>
            <li><a href="?page=<?php echo $page+1; ?><?php echo isset($cat)?"&category={$cat}":''; ?><?php echo isset($sta)?"&status={$sta}":''; ?>">下一页</a></li>
          <?php endif ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox" id="all"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
         <?php foreach ($posts as $item): ?>
            <tr>
            <td class="text-center"><input type="checkbox" name="sub" data-id="<?php echo $item['id'] ?>"></td>
            <td><?php echo $item['title']; ?></td>
            <td><?php echo $item['user_name']; ?></td>
            <td><?php echo $item['category_name']; ?></td>
            <td class="text-center"><?php echo convert_date($item['created']); ?></td>
            <td class="text-center"><?php echo convert_status($item['status']); ?></td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="/admin/posts-delete.php?page=<?php echo $page ?>&id=<?php echo $item['id'] ?><?php echo isset($cat)?"&category={$cat}":''; ?><?php echo isset($sta)?"&status={$sta}":''; ?>" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
         <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
<?php $gaoliang='posts'; ?>
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
        $btnDelete.attr('href','/admin/posts-delete.php?page=<?php echo $page ?>&id='+allCheckeds<?php echo isset($cat)?"+'&category={$cat}'":''; ?><?php echo isset($sta)?"+'&status={$sta}'":''; ?>);
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
        $btnDelete.attr('href','/admin/posts-delete.php?page=<?php echo $page ?>&id='+allCheckeds<?php echo isset($cat)?"+'&category={$cat}'":''; ?><?php echo isset($sta)?"+'&status={$sta}'":''; ?>);
      });
    });
  </script>
  <script>NProgress.done()</script>
</body>
</html>
