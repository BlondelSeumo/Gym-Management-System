<?php
include 'connection.php';

$result=array();

$id = $_REQUEST['id'];

$token = '';
if(isset($id)){
	$update = "update gym_member set token='$token' where id = '$id'";

	if($data = $conn->query($update))
	{
		$result['status']='1';
		$result['error_code']=200;
		$result['error']=custom_http_response_code(200);
	}else{
		$result['status']='0';
		$result['error_code']=401;
		$result['error']=custom_http_response_code(401);
	}
}
else{
	$result['status']='0';
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
}

echo json_encode($result);
?>