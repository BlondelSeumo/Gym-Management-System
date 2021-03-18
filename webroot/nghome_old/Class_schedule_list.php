<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$get_record="SELECT gmc.*,cs.class_name,csl.* FROM `gym_member_class` as gmc,class_schedule as cs,class_schedule_list as csl  WHERE gmc.assign_class=cs.id and csl.class_id=gmc.assign_class and gmc.member_id=$id ORDER BY CAST(csl.start_time AS TIME) ASC ";
$select_query=$conn->query($get_record);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query)){
		$new_days=implode(",",json_decode($get_data['days']));
		$get_data['days']=$new_days;
		$get_data['start_time']=substr_replace($get_data['start_time'], ':', -3, -2);
		$get_data['end_time']=substr_replace($get_data['end_time'], ':', -3, -2);
		$result['result'][]=$get_data;
	}
}else
{
	$result['status']='0';
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
	$result['result']=array();
}
echo json_encode($result);
?>