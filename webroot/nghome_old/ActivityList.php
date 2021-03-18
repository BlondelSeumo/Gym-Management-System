<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$r="SELECT `selected_membership` FROM `gym_member` WHERE `id`= $id";
$res=$conn->query($r);
$id=mysqli_fetch_assoc($res)['selected_membership'];
$get_record="SELECT c.first_name,c.last_name,b.title,d.name,e.video FROM membership_activity a
LEFT JOIN activity b ON a.activity_id = b. id
LEFT JOIN  gym_member c ON b.assigned_to = c.id
LEFT JOIN category d ON b.cat_id=d.id
LEFT JOIN activity_video e ON e.activity_id= b.id 
where a.membership_id=$id";
$select_query=$conn->query($get_record);

$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query)){
		if($get_data['video'] != null){
			$iframe = $get_data['video'];
			$data = explode(" ",$iframe);
			$get_data['video'] = substr($data[3], 6);
		}
		
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
