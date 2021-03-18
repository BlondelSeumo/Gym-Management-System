<?php
include("connection.php");

$query="SELECT `username` FROM `gym_member` ";
$sql=$conn->query($query);
$result['status']="1";
$result['error']=custom_http_response_code(200);
if($sql->num_rows > 0)
{
	while($row=$sql->fetch_assoc())
	{
		$result['username'][]=$row['username'];
	}
}
else{
		$result['username'][]=array();
}

$res=$conn->query("SELECT `id` FROM `gym_member` ORDER BY `id` DESC LIMIT 1");
$lastid=$res->fetch_assoc()['id'];
$lastid = ($lastid != null) ? $lastid + 1 : 01 ;
$m = date("d");
$y = date("y");
$prefix = "M".$lastid;
$member_id = $prefix.$m.$y;
$result['result']['member_id']=$member_id;
//Class List
$sql="SELECT `id`,`class_name` FROM `class_schedule`";
$res=$conn->query($sql);
if($res->num_rows > 0)
{
	while($row=$res->fetch_assoc())
	{
		$result['result']['class'][]=$row;
	}
}
else
{
	$result['result']['class']=array();
}
//Group list
$sql="SELECT `id`,`name` FROM `gym_group`";
$res=$conn->query($sql);
if($res->num_rows > 0)
{
	while($row=$res->fetch_assoc())
	{
		$result['result']['group'][]=$row;
	}
}
else
{
	$result['result']['group']=array();
}
//Interest area
$sql="SELECT * FROM `gym_interest_area`";
$res=$conn->query($sql);
if($res->num_rows > 0)
{
	while($row=$res->fetch_assoc())
	{
		$result['result']['interest_area'][]=$row;
	}
}
else
{
	$result['result']['interest_area']=array();
}
//Membership
$sql="SELECT `id`,`membership_label` FROM `membership`";
$res=$conn->query($sql);
if($res->num_rows > 0)
{
	while($row=$res->fetch_assoc())
	{
		$result['result']['membership'][]=$row;
	}
}
else
{
	$result['result']['membership']=array();
}

echo json_encode($result);

?>