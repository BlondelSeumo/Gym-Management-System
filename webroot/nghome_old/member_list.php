<?php
include("connection.php");
$sql="SELECT * FROM `gym_member` WHERE `role_name` = 'member'";
$result1=$conn->query($sql);
if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $result1->fetch_assoc())
	{
		$row['name']=$row['first_name']." ".$row['last_name'];
		$row['image']=$image_path.$row['image'];
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
