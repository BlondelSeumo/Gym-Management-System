<?php
include('connection.php');
if(isset($_REQUEST['date'])){$date=$_REQUEST['date'];}
if(isset($_REQUEST['id'])){$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));}
$query="SELECT `id`,`note` FROM `gym_daily_workout` WHERE `record_date`='$date' AND `member_id` = $id";
$res=$conn->query($query);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $res->fetch_assoc())
	{
		$sql="SELECT  `id`,`workout_name`, `sets`, `reps`, `kg`,`rest_time` FROM `gym_user_workout` WHERE `user_workout_id`='".$row['id']."'";
		$res1=$conn->query($sql);
		if ($res1->num_rows > 0)
		{
			while($r = $res1->fetch_assoc())
			{
				$r['workout_name']=workout_name($r['workout_name']);
				$r['note']=$row['note'];
				$result['result']['workout'][]=$r;
			}
		}
		else
		{
			$result['status']='0';
			//$result['error_code']=404;
			//$result['error']=custom_http_response_code(404);
			$result['error_code']=204;
			$result['error']=custom_http_response_code(204);
			$result['result']=array();
		}
	}
}
else
{
	$result['status']='0';
	$result['error_code']=400;
	$result['error']=custom_http_response_code(400);
	$result['result']=array();

}
function workout_name($id)
{
	include('connection.php');
	$sql="SELECT `title` FROM `activity` WHERE `id`=$id";
	return $conn->query($sql)->fetch_assoc()['title'];
}
echo json_encode($result);
$conn->close();
?>
