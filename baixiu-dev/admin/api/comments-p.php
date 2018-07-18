<?php

require_once '../../functions.php';
header('Content-Type:application/json');
if(empty($_GET['id'])||empty($_GET['p'])){
	exit(json_encode(false));
}

$id=$_GET['id'];
$comments=$_GET['p'];

switch ($comments) {
	case 'rejected':
		$row = baixiu_execute("update comments set status='rejected' where id in ({$id});");
		echo json_encode($row);
		break;
	case 'actived':
		$row = baixiu_execute("update comments set status='actived' where id in ({$id});");
		echo json_encode($row);
		break;
	case 'delete':
		$row = baixiu_execute('delete from comments where id in ('.$id.');');
		echo json_encode($row);
		break;
	default:
		echo json_encode(false);		
		break;
}
