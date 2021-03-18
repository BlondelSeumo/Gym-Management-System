<?php
include("connection.php");
$id=intval(mysqli_real_escape_string($conn,$_REQUEST["id"]));
$sql="SELECT `id`,`start_date`,`end_date` FROM `gym_assign_workout` WHERE `user_id`=$id";
$result = array();
$query=$conn->query($sql);
if($query->num_rows>0)
{
	$result['status']="1";
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($r = $query->fetch_assoc())
	{

		$sql="SELECT `day_name`,activity.`title`,`sets`,`reps`,`kg`,`time` FROM `gym_workout_data` INNER JOIN `activity`
		ON gym_workout_data.workout_name=activity.id WHERE gym_workout_data.workout_id='".$r['id']."'
		ORDER BY CASE WHEN day_name = 'Sunday' THEN 1 WHEN day_name = 'Monday' THEN 2 WHEN day_name = 'Tuesday'
		THEN 3 WHEN day_name = 'Wednesday' THEN 4
		WHEN day_name = 'Thursday' THEN 5 WHEN day_name = 'Friday' THEN 6 WHEN day_name = 'Saturday' THEN 7 END ASC
		" ;
		$query1=$conn->query($sql);
		if($query1->num_rows>0)
		{
			while($r1 = $query1->fetch_assoc())
			{
				$r['days'][]=$r1;
			}
			$result['result'][]=$r;
		}

	}
}
else
{
	$result['status']="0";
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
}
echo json_encode($result);
?>
