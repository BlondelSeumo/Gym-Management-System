<?php
include'connection.php';
//$Role_name=$_REQUEST['r_name'];
/*$get_record="SELECT gym_member.image,gym_member.first_name,gym_member.last_name,gym_member.email,
gym_member.mobile,gym_roles.name FROM `gym_member` INNER JOIN gym_roles ON gym_roles.id=gym_member.role";*/

$get_record="SELECT gym_member.image,gym_member.first_name,gym_member.last_name,gym_member.email,
gym_member.mobile,gym_member.role_name FROM `gym_member` where gym_member.role_name = 'staff_member'";
$Select_query=$conn->query($get_record);
$result=array();
if(mysqli_num_rows($Select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($Select_query))
	{
		$get_data['image']=$image_path.$get_data['image'];
	//	$Role_name=$get_data['role_name'];
		$result['result'][]=$get_data;
	}
}else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']=array();
}
echo json_encode($result);
?>
