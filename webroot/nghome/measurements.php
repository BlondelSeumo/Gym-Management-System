
<?php
include("connection.php");
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$sql="SELECT `result`,`result_date` FROM `gym_measurement` WHERE `result_measurment`='weight' AND `user_id`= $id";
$res=$conn->query($sql);
if($res->num_rows>0)
{
	while($r=$res->fetch_assoc())
	{
		$result['weight'][]=$r;
	}
}
else{$result['weight']=array();}
$sql="SELECT `result`,`result_date` FROM `gym_measurement` WHERE `result_measurment`='Waist' AND `user_id`= $id";
$res=$conn->query($sql);
if($res->num_rows>0)
{
	while($r1=$res->fetch_assoc())
	{
		$result['Waist'][]=$r1;
	}
}
else
{
	$result['Waist']=array();
}
$sql="SELECT `result`,`result_date` FROM `gym_measurement` WHERE `result_measurment`='height' AND `user_id`= $id";
$res=$conn->query($sql);
if($res->num_rows>0)
{
	while($r2=$res->fetch_assoc())
	{
		$result['height'][]=$r2;
	}
}else{$result['height']=array();}
$sql="SELECT `result`,`result_date` FROM `gym_measurement` WHERE `result_measurment`='thigh' AND `user_id`= $id";
$res=$conn->query($sql);

if($res->num_rows>0)
{
	while($r3=$res->fetch_assoc())
	{
		$result['Thigh'][]=$r3;
	}
}else{$result['Thigh']=array();}

$sql="SELECT `result`,`result_date` FROM `gym_measurement` WHERE `result_measurment`='arms' AND `user_id`= $id";
$res=$conn->query($sql);

if($res->num_rows>0)
{
	while($r4=$res->fetch_assoc())
	{
		$result['Arms'][]=$r4;
	}
}else{$result['Arms']=array();}

$sql="SELECT `result`,`result_date` FROM `gym_measurement` WHERE `result_measurment`='chest' AND `user_id`= $id";
$res=$conn->query($sql);
if($res->num_rows>0)
{
	while($r5=$res->fetch_assoc())
	{
		$result['Chest'][]=$r5;
	}
}
else{$result['Chest']=array();}

$sql="SELECT `result`,`result_date` FROM `gym_measurement` WHERE `result_measurment`='fat' AND `user_id`= $id";
$res=$conn->query($sql);

if($res->num_rows>0)
{
	while($r6=$res->fetch_assoc())
	{
		$result['Fat'][]=$r6;
	}
}
else{
	$result['Fat']=array();
}
echo json_encode($result);
?>