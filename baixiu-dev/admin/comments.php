<?php 
require_once '../functions.php';

baixiu_get_current_user();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <style>
    #loading{
      display:none;
      align-items: center;
      justify-content: center;
      position:fixed;
      left: 0;
      top: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0,0,0,.7);
      z-index: 999;
    } 
    .line-spin-fade-loader {
      position: relative;
      top: -10px;
      left: -4px;
      transform: scale(1);
    }
    
    .line-spin-fade-loader > div:nth-child(1) {
      top: 20px;
      left: 0;
      -webkit-animation: line-spin-fade-loader 1.2s -0.84s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s -0.84s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div:nth-child(2) {
      top: 13.63636px;
      left: 13.63636px;
      -webkit-transform: rotate(-45deg);
      transform: rotate(-45deg);
      -webkit-animation: line-spin-fade-loader 1.2s -0.72s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s -0.72s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div:nth-child(3) {
      top: 0;
      left: 20px;
      -webkit-transform: rotate(90deg);
      transform: rotate(90deg);
      -webkit-animation: line-spin-fade-loader 1.2s -0.60s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s -0.60s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div:nth-child(4) {
      top: -13.63636px;
      left: 13.63636px;
      -webkit-transform: rotate(45deg);
      transform: rotate(45deg);
      -webkit-animation: line-spin-fade-loader 1.2s -0.48s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s -0.48s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div:nth-child(5) {
      top: -20px;
      left: 0;
      -webkit-animation: line-spin-fade-loader 1.2s -0.36s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s -0.36s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div:nth-child(6) {
      top: -13.63636px;
      left: -13.63636px;
      -webkit-transform: rotate(-45deg);
      transform: rotate(-45deg);
      -webkit-animation: line-spin-fade-loader 1.2s -0.24s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s -0.24s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div:nth-child(7) {
      top: 0;
      left: -20px;
      -webkit-transform: rotate(90deg);
      transform: rotate(90deg);
      -webkit-animation: line-spin-fade-loader 1.2s -0.12s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s -0.12s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div:nth-child(8) {
      top: 13.63636px;
      left: -13.63636px;
      -webkit-transform: rotate(45deg);
      transform: rotate(45deg);
      -webkit-animation: line-spin-fade-loader 1.2s 0s infinite ease-in-out;
      animation: line-spin-fade-loader 1.2s 0s infinite ease-in-out;
    }
    
    .line-spin-fade-loader > div {
      background-color: #1487FF;
      width: 4px;
      height: 35px;
      border-radius: 2px;
      margin: 2px;
      -webkit-animation-fill-mode: both;
      animation-fill-mode: both;
      position: absolute;
      width: 5px;
      height: 15px;
    }

    @-webkit-keyframes line-spin-fade-loader {
      50% {
        opacity: 0.3;
      }
    
      100% {
        opacity: 1;
      }
    }
    
    @keyframes line-spin-fade-loader {
      50% {
        opacity: 0.3;
      }
    
      100% {
        opacity: 1;
      }
    }
  </style>
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>
  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <a href="javascript:;" class="btn btn-info btn-sm p-actived">批量批准</a>
          <a href="javascript:;" class="btn btn-warning btn-sm p-rejected">批量拒绝</a>
          <a href="javascript:;" class="btn btn-danger btn-sm p-delete">批量删除</a>
        </div>
        <ul class="pagination pagination-sm pull-right"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox" id="all"></th>
            <th class="text-center" width="50">作者</th>
            <th>评论</th>
            <th class="text-center" width="60">评论在</th>
            <th class="text-center" width="150">提交于</th>
            <th class="text-center" width="50">状态</th>
            <th class="text-center" width="150">操作</th>
          </tr>
        </thead>
        <tbody>
          <!-- <tr class="danger">
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>未批准</td>
            <td class="text-center">
              <a href="post-add.php" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr> -->
        </tbody>
      </table>
    </div>
  </div>
  <?php $gaoliang='comments'; ?>
  <?php include 'inc/sidebar.php'; ?>  
  <div id="loading">
    <div class="line-spin-fade-loader">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    </div>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
      <tr{{if status == 'held'}} class="warning"{{else status == 'rejected'}} class="danger"{{/if}} data-id="{{:id}}">
            <td class="text-center"><input type="checkbox" name="sub"></td>
            <td>{{:author}}</td>
            <td>{{:content}}</td>
            <td>{{:post_title}}</td>
            <td>{{:created}}</td>
            <td>{{if status == 'held'}}待审{{else status == 'rejected'}}拒绝{{else}}准许{{/if}}</td>
            <td class="text-center">
              {{if status == 'held'}}
              <a href="javascript:;" class="btn btn-info btn-xs btn-actived">批准</a>
              <a href="javascript:;" class="btn btn-warning btn-xs btn-rejected">拒绝</a>
              {{/if}}
              <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
            </td>
          </tr>
    {{/for}}
  </script>
  <script>
    //发送ajax请求获取所需数据，渲染到页面
    // $.getJSON('/admin/api/comments.php?',{page:1},function(res){
    //   //使用jsrender库
    //   var html = $('#comments_tmpl').render({
    //     comments:res
    //   });
    //   $('tbody').html(html);
    // });
    $(document)
    .ajaxStart(function(){
      $("#loading").stop().css("display","flex");
    })
    .ajaxStop(function(){
      $("#loading").stop().css("display","none");
    })

    var currentPage = 1;
    var totalPages = 1;
    var $sub = null;
    var $all = $("#all");   
    var allCheckeds = [];


    function twb(page,tPages){
      $(".pagination").twbsPagination('destroy');
      $(".pagination").twbsPagination({
          first:'首页',
          prev:'上一页',
          next:'下一页',
          last:'末页',
          startPage:page,//因为这是重新渲染的，所以要设置高亮显示当前页面，否则默认为1
          totalPages:tPages,
          visiablePages:5,
          initiateStartPageClick:false,
          onPageClick:function(e,page){
          loadPageData(page);
        }
      });
    }

    function loadPageData(page){
      $.getJSON('/admin/api/comments.php?',{page:page},function(res){
        $(".pagination").twbsPagination({
          first:'首页',
          prev:'上一页',
          next:'下一页',
          last:'末页',
          totalPages:res.total_pages,
          visiablePages:5,
          initiateStartPageClick:false,//默认是true，初始化就加载onPageClick一次
          onPageClick:function(e,page){
          loadPageData(page);
        }
      });
      totalPages = res.total_pages;
      twb(page,totalPages);

      //使用jsrender模板渲染页面
      var html = $('#comments_tmpl').render({
        comments:res.comments
      });
      $('tbody').fadeOut(function(){
        allCheckeds = [];
        $all.prop('checked',false);
        allCheckeds.length ? $(".btn-batch").fadeIn() : $(".btn-batch").fadeOut();
        $(this).html(html).fadeIn();
        currentPage = page;
        $sub = $("input[name='sub']");
      });
    });
    }
    loadPageData(currentPage);
   

    //删除
    //因为删除按钮动态添加，所以需要注册事件委托，给tbody注册

    $('tbody')
    .on('click','.btn-delete',function(){
      //先拿到需要删除的ID
      var $tr = $(this).parent().parent();
      var id = $tr.data('id');
      
      //发ajax告诉服务端要删的数据
      $.get('/admin/api/comments-p.php',{id:id,p:'delete'},function(res){
        if(res){
          //服务端返回删除结果是否成功，再决定是否界面移除数据
          // $tr.remove();
          // 其实应该重新加载当前页
          if($('tbody').children().length>1){
            loadPageData(currentPage);
          } else if(currentPage<totalPages){
            loadPageData(currentPage);
          } else if(currentPage!==1){
            loadPageData(currentPage-1);
          } else {
            $tr.remove();
          }
        }
      });
    })
    .on('click','.btn-actived',function(){
      var id = $(this).parent().parent().data('id');
      $.get('/admin/api/comments-p.php',{id:id,p:'actived'},function(res){
        loadPageData(currentPage);
      });
    })
    .on('click','.btn-rejected',function(){
      var id = $(this).parent().parent().data('id');
      $.get('/admin/api/comments-p.php',{id:id,p:'rejected'},function(res){
        loadPageData(currentPage);
      });
    })
    .on('change',"input[name='sub']",function(){
      var $tr = $(this).parent().parent();
      var id = $tr.data('id');
      if($(this).prop('checked')){
        allCheckeds.push(id);
      }else {
        allCheckeds.splice(allCheckeds.indexOf(id),1)
      }
      allCheckeds.length ? $(".btn-batch").fadeIn() : $(".btn-batch").fadeOut();
      if(allCheckeds.length==$sub.length){
        $all.prop('checked',true);
      }else{
        $all.prop('checked',false);
      }
    });

    $all.on('change',function(){
      allCheckeds = [];
      $sub.prop('checked',$(this).prop('checked'));
      if($(this).prop('checked')){
        for(var i = 0;i<$sub.length;i++){
          allCheckeds.push($sub.eq(i).parent().parent().data('id'));
        }
      }
      allCheckeds.length ? $(".btn-batch").fadeIn() : $(".btn-batch").fadeOut();
    });

    $(".p-delete").on('click',function(){
      $.get('/admin/api/comments-p.php',{id:allCheckeds.join(','),p:'delete'},function(res){
        allCheckeds = [];
        // console.log("res:"+res+"==allCheckeds.length:"+allCheckeds.length+"==$sub.length:"+$sub.length+"==totalPages:"+totalPages+"==currentPage:"+currentPage);
        if(res){ 
          $all.prop('checked',false);
          if(res<$sub.length){
            loadPageData(currentPage);
          } else if(res=$sub.length){
          //   if(currentPage==totalPages){
          //     loadPageData(currentPage);
          //   }else {
          //   $('tbody').children().remove();
          // }
          if(currentPage<totalPages){
            loadPageData(currentPage);
          }else{
            if(currentPage!==1){
              loadPageData(currentPage-1);
            }else{
              $('tbody').children().remove();
            }
          }





          } 
        }else {
          alert('删除操作失败哦~缺少参数！');
          return false;
        }

      });
    });

    $(".p-actived").on('click',function(){
      $.get('/admin/api/comments-p.php',{id:allCheckeds.join(','),p:'actived'},function(res){
        allCheckeds = [];
        loadPageData(currentPage);
      })
    });
    $(".p-rejected").on('click',function(){
      $.get('/admin/api/comments-p.php',{id:allCheckeds.join(','),p:'rejected'},function(res){
        allCheckeds = [];
        loadPageData(currentPage);

      })
    });

  </script>
  <script>NProgress.done()</script>
</body>
</html>
