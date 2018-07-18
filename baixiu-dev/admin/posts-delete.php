<?php
//根据客户端传递过来的ID删除数据
require_once '../functions.php';

if(empty($_GET['id'])){
	exit('缺少必要参数');
}

$id = $_GET['id'];

$page=empty($_GET['page'])?'1':$_GET['page'];

$rows = baixiu_execute("delete from posts where id in ({$id});");

$url = "/admin/posts.php?page={$page}";
isset($_GET['category'])?$url.="&category={$_GET['category']}":'';
isset($_GET['status'])?$url.="&status={$_GET['status']}":'';

header("Location: {$url}");