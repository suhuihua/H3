<?php
//接收客户端ajax请求，返回评论数据

require_once '../../functions.php';

$page = empty($_GET['page'])?1:intval($_GET['page']);

$length = 50;

$offset = ($page-1)*$length;

$sql = sprintf('
select 
comments.*,
posts.title as post_title 
from comments
inner join posts on comments.post_id = posts.id
order by comments.created desc
limit %d,%d;',$offset,$length);

$comments = baixiu_fetch_all($sql);

$total_count = baixiu_fetch_one('select count(1) as num 
	from comments
	inner join posts on comments.post_id = posts.id;')['num'];
$total_pages = ceil($total_count/$length);
//网络传输通过字符串传输，先将数据转成字符串
$json = json_encode(array(
	'total_pages'=>$total_pages,
	'comments'=>$comments
));

header('Content-Type: application/json');

echo $json;