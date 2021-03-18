<?php
include('connection.php');
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$sql="SELECT `member_type` FROM `gym_member` WHERE `id`=$id";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	$mType=$res->fetch_assoc()['member_type'];
	if($mType=='Member')
	{
		$result['result'][]="Paypal";
	}
}
else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']=array();

}
echo json_encode($result);
$conn->close();
