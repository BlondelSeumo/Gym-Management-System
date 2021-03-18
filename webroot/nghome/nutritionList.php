<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$sql1="SELECT `id` FROM `gym_nutrition` WHERE `user_id`=$id";
$select_query=$conn->query($sql1);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($r=mysqli_fetch_assoc($select_query)){
		$sql="SELECT `day_name`, `nutrition_time`, `nutrition_value` FROM `gym_nutrition_data`
		WHERE `nutrition_id`='".$r['id']."'";
		$query=$conn->query($sql);
		if(mysqli_num_rows($query) > 0){
			while($r=mysqli_fetch_assoc($query)){
				$result['result'][]=$r;
			}
		}
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
