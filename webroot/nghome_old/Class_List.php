<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$sql="SELECT `assign_class` FROM `gym_member_class` WHERE `member_id`=$id";
$select_query=$conn->query($sql);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	while($get_data=mysqli_fetch_assoc($select_query)){
		$r[]=$get_data['assign_class'];
	}
}
for($i=0;$i<sizeof($r);$i++)
{
$get_record="SELECT class_schedule.class_name,gym_member.first_name,gym_member.last_name,
class_schedule.start_time,class_schedule.end_time,class_schedule.location FROM 
`class_schedule` INNER JOIN gym_member ON gym_member.id=class_schedule.assign_staff_mem WHERE 
class_schedule.id ='".$r[$i]."'";
$select_query=$conn->query($get_record);

if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query))
	{
		$get_data['start_time']=substr_replace($get_data['start_time'], ':', -3, -2);
		$get_data['end_time']=substr_replace($get_data['end_time'], ':', -3, -2);
		$result['result'][]=$get_data;
	}
}else{
	$result['status']='0';
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
	$result['result']=array();
	}
}
echo json_encode($result);
?>