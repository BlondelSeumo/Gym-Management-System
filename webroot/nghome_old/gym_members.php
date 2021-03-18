<?php
include("connection.php");
$role=mysqli_real_escape_string($conn,$_REQUEST['role']);
if($role=='0'){$role="member";}
elseif($role=='1'){$role="staff_member";}
elseif($role=='2'){$role="accountant";}
elseif($role=='3'){$role="administrator";}
else{$role="member";}
$query="SELECT `id`,`first_name`,`last_name` FROM `gym_member` WHERE `role_name`='$role'";
$res=$conn->query($query);
$result=array();
if ($res->num_rows > 0)
{
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($row = $res->fetch_assoc())
	{
		$r['name']=$row['first_name']." ".$row['last_name'];
		if($role=='administrator'){$r['name']='Administrator';}
		$r['id']=$row['id'];
		$result['result']['members'][]=$r;
	}
}
else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']=null;
}
echo json_encode($result);
?>
