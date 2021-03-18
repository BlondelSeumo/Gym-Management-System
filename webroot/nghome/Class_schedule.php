<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$get_record="SELECT class_schedule_list.days,class_schedule.class_name,class_schedule_list.start_time,
class_schedule_list.end_time FROM `class_schedule_list` INNER JOIN 
class_schedule ON class_schedule_list.class_id=class_schedule.id WHERE class_id=(SELECT `assign_class` FROM `gym_member` WHERE `id`=$id)";

    $select_query=$conn->query($get_record);
    $result=array();
   
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query)){
		$new_days=implode(",",json_decode($get_data['days']));
		$get_data['days']=$new_days;
		$result['result'][]=$get_data;
	}
    
}else{
	$result['status']='0';
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
	$result['result']=array();
}

echo json_encode($result);
?>