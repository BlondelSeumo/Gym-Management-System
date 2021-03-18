<?php
include('connection.php');
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$sql="DELETE FROM `gym_measurement` WHERE `id`=$id"; 
$result=array();
if ($conn->query($sql)) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
} 
else
{
	$result['status']='0';
	$result['error_code']=400;
	$result['error']=custom_http_response_code(400);
}
echo json_encode($result);
$conn->close();
?>