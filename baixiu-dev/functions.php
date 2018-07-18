<?php 
require_once 'config.php';

session_start();
//公用函数





//获取当前用户，没有的话就跳到登录页面
function baixiu_get_current_user(){
	if(empty($_SESSION['current_login_user'])){
  		header('Location: /admin/login.php');
  		exit();//后面的代码不执行
	}
	return $_SESSION['current_login_user'];
}



//多条数据。索引数组
function baixiu_fetch_all($sql){
	$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	if(!$conn) {
		exit('连接失败');
	}
	mysqli_query($conn,"set names 'utf8' ");  
	mysqli_query($conn,"set character_set_client=utf8");   
	mysqli_query($conn,"set character_set_results=utf8");
	$query=mysqli_query($conn,$sql);
	if(!$query){
		//查询失败
		return false;
	}
	while ($row=mysqli_fetch_assoc($query)) {
		$res[]=$row;
	}

	mysqli_free_result($query);
	mysqli_close($conn);
	return $res;
}


//一条数据，关联数组
function baixiu_fetch_one($sql){
	$res = baixiu_fetch_all($sql);
	return isset($res[0])?$res[0]:null;
}

function baixiu_execute($sql){
	$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	if(!$conn) {
		exit('连接失败');
	}
	mysqli_query($conn,"set names 'utf8' ");  
	mysqli_query($conn,"set character_set_client=utf8");   
	mysqli_query($conn,"set character_set_results=utf8");
	$query=mysqli_query($conn,$sql);
	if(!$query){
		//查询失败
		return false;
	}
	$res=mysqli_affected_rows($conn);

	// mysqli_free_result($query);
	mysqli_close($conn);
	return $res;
}