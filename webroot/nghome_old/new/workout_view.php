<?php
include('connection.php');
if(isset($_REQUEST['date'])){$date=$_REQUEST['date'];}
if(isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
$query="SELECT `id` FROM `gym_daily_workout` WHERE `record_date`='$date' AND `member_id` = 6";
$res=$conn->query($query);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $res->fetch_assoc()) 
	{
		$sql="SELECT  `id`,`workout_name`, `sets`, `reps`, `kg`,`rest_time` FROM `gym_user_workout` WHERE `user_workout_id`='".$row['id']."'";
		$res1=$conn->query($sql);
		if ($res1->num_rows > 0) 
		{
			while($r = $res1->fetch_assoc()) 
			{
				$r['workout_name']=workout_name($r['workout_name']);
				$result['result']['workout'][]=$r;
			}
		}
		else
		{
			$result['status']='0';
			$result['error']='No records!';
			$result['result']=array();
		}
	}
} 
else
{
	$result['status']='0';
	$result['error']='No records!';
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