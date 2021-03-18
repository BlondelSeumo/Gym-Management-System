<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));

$get_record="SELECT `notice_title`,`comment`,`notice_for`,`start_date`,`end_date`,`class_id` FROM `gym_notice` WHERE `notice_for`='member' OR `notice_for`='all'";

$select_query=$conn->query($get_record);
if(mysqli_num_rows($select_query) > 0)
{
	$result['status']='1';
	$result['error']=custom_http_response_code(200);
	while($res=mysqli_fetch_assoc($select_query)){
		$res['class_name']=classname($res['class_id']);
		unset($res['class_id']);
		$res['start_date'] =  date('F d,Y');
		$res['end_date'] =  date('F d,Y');
		$result['result'][]=$res;
	}
	
	$sql = "SELECT `notice_title`,`comment`,`notice_for`,`start_date`,`end_date`,class_id FROM `gym_notice` WHERE gym_notice.class_id IN(SELECT `assign_class` FROM `gym_member_class` WHERE `member_id`=$id)";
	$select_query1=$conn->query($sql);
	
	if(mysqli_num_rows($select_query1) > 0)
	{
		$result['status']='1';
		$result['error']=custom_http_response_code(200);
		while($get_data=mysqli_fetch_assoc($select_query)){
			//$result['result'][]=$get_data;
			$res['class_name']=classname($res['class_id']);
			unset($res['class_id']);
			$res['start_date'] =  date('F d,Y');
			$res['end_date'] =  date('F d,Y');
			$result['result'][]=$res;
		}
	}
	
}
else{
	$result['status']='0';
	$result['result'] = array();
	$result['error'] = custom_http_response_code(400);

}
function classname($id)
{
	if($id==0)
	{
		return "none";
	}
	else
	{
	global $conn;
	$sql="SELECT `class_name` FROM `class_schedule` WHERE `id`=$id";
	return mysqli_fetch_assoc($conn->query($sql))['class_name'];
	}
}
echo json_encode($result);
?>