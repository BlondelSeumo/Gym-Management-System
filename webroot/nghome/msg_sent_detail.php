<?php
include("connection.php");
if(isset($_REQUEST['mp_id'])){$id=intval(mysqli_real_escape_string($conn,$_REQUEST['mp_id']));}
$sql="SELECT * FROM `gym_message` WHERE `sender`= $id AND `status`=1";
 $result=array();
$result1=$conn->query($sql);
if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $result1->fetch_assoc())
	{
		$receiver=$row['receiver'];
		$sql="SELECT `email` FROM `gym_member` WHERE id= $receiver";
		$r=$conn->query($sql)->fetch_assoc();
		$row['email']=$r['email'];
		$date = new DateTime($row['date']);
		$new_date = $date->format('d/m/Y,h:i:s a');
		$row['date']=$new_date;
		$result['result'][]=$row;
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
