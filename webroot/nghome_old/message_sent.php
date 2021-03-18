<?php
include("connection.php");
if(isset($_REQUEST['id'])){$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));}
$sql="SELECT * FROM `gym_message` WHERE `sender`= '$id' ORDER BY `date` DESC";
$result1=$conn->query($sql);
$result=array();
if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $result1->fetch_assoc())
	{

		$query="SELECT `image`,`first_name`,`last_name` FROM `gym_member` WHERE `id`='".$row['receiver']."'";
		$result2=$conn->query($query);
		if ($result2->num_rows > 0)
		{
			$r1=$result2->fetch_assoc();
			$row['sender']=$r1['first_name']." ".$r1['last_name'];
			$row['image']=$image_path.$r1['image'];
			$date = new DateTime($row['date']);
			$new_date = $date->format('h:i A');
			$row['time']=$new_date;
			$row['date'] = $date->format('d-M-Y h:i A');
			$result['result']['messageInbox'][]=$row;
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
	$result['result']=array();

}
echo json_encode($result);
$conn->close();
?>
