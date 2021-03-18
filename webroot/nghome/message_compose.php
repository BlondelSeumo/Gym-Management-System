<?php
include("connection.php");
if(isset($_REQUEST['id'])){$sender=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));}
if(isset($_REQUEST['receiver'])){$receiver=$_REQUEST['receiver'];}
if(isset($_REQUEST['class'])){$class=$_REQUEST['class'];}
if(isset($_REQUEST['subject'])){$subject=$_REQUEST['subject'];}
if(isset($_REQUEST['msg_body'])){$msg_body=$_REQUEST['msg_body'];}
if($receiver=='-4'){$rec='member';}
else if($receiver=='-3'){$rec='staff_member';}
else if($receiver=='-2'){$rec='accountant';}
else if($receiver=='-1'){$rec='administrator';}
$result=array();
function send($id)
{
    date_default_timezone_set("Asia/Kolkata");
	global $sender,$subject,$msg_body,$date,$conn,$result;
    $date = date("Y-m-d H:i:s");
	$sql="INSERT INTO `gym_message` (`sender`, `receiver`, `date`, `subject`, `message_body`, `status`)
	VALUES ($sender,$id,'$date','$subject','$msg_body',1)";
	//echo json_encode($sql);die;
	if ($conn->query($sql)) {
		$result['status']='1';
		$result['error_code']=200;
		$result['error']=custom_http_response_code(200);
	}
	else
	{
		$result['status']='0';
		//$result['error_code']=404;
		//$result['error']=custom_http_response_code(404);
		$result['error_code']=204;
		$result['error']=custom_http_response_code(204);
	}
}
$Temp=array();
if($receiver<0)
{
	$sql="SELECT `id` FROM `gym_member` WHERE `role_name`='$rec'";
	$res=$conn->query($sql);
	if ($res->num_rows > 0)
	{
		while($row = $res->fetch_assoc())
		{
			array_push($Temp,$row['id']);
			send($row['id']);
		}
	}
}
else
{
	array_push($Temp,$receiver);
	send($receiver);
}
if($class=='-2')
{
	$sql="SELECT DISTINCT `member_id` FROM `gym_member_class`";
	$res=$conn->query($sql);
	if ($res->num_rows > 0)
	{
		while($row1 = $res->fetch_assoc())
		{
			if (!in_array($row1['member_id'], $Temp))
			{
				send($row1['member_id']);
			}
		}

	}

}
else if($class>=0)
{
	$sql="SELECT `member_id` FROM `gym_member_class` WHERE `assign_class`=$class";
	$res=$conn->query($sql);
	if ($res->num_rows > 0)
	{
		while($row2 = $res->fetch_assoc())
		{
			if (!in_array($row2['member_id'], $Temp))
			{
				send($row2['member_id']);
			}
		}
	}
}
echo json_encode($result);
$conn->close();
//SELECT `member_id` FROM `gym_member_class` WHERE `assign_class`
?>
