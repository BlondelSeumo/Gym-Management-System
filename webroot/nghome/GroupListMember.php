<?php
include'connection.php';
$gp_id=intval(mysqli_real_escape_string($conn,$_REQUEST['group_id']));
$get_record="SELECT `image`,`first_name`,`last_name` FROM `gym_member` WHERE `role_name`='member' and assign_group LIKE '%$gp_id%'";
$array=array();
$rs=mysqli_query($conn,$get_record);
while($get_rows=mysqli_fetch_assoc($rs)){
	$get_rows['image']=$image_path.$get_rows['image'];
	$result['result']=$get_rows;
}

echo json_encode($result);

die;

$get_record="SELECT `image`,`first_name`,`last_name` FROM `gym_member` WHERE assign_group LIKE '%1%'";
$Select_query=$conn->query($get_record);
$result=array();
if(mysqli_num_rows($Select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($Select_query))
	{
		$get_data['image']=$image_path.$get_data['image'];
       // $Assign_group=$get_data['assign_group'];
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
