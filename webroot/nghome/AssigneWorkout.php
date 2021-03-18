<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$sql="SELECT `id` FROM `gym_assign_workout` WHERE `user_id` =$id";
$select_query=$conn->query($sql);
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query)){
			$id=$get_data['id'];
			$sql="SELECT `workout_name`,`day_name`,`reps`,`sets`,`kg`,`time` FROM `gym_workout_data` WHERE `workout_id`=$id";
			$query=$conn->query($sql);
			if(mysqli_num_rows($query) > 0)
			{
				while($r=mysqli_fetch_assoc($query))
				{
					$r['workout_name']=workout($r['workout_name']);
					$result['result'][]=$r;
				}
		}
	}
}
else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
}
function workout($wid)
{
	global $conn;
	$sql="SELECT `title` FROM `activity` WHERE `id`=$wid";
	$select_query=$conn->query($sql);
	$get_data=mysqli_fetch_assoc($select_query);
	return $get_data['title'];
}
echo json_encode($result);

?>
