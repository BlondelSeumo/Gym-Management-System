<?php
include("connection.php");
if(isset($_REQUEST['mp_id'])){$id=intval(mysqli_real_escape_string($conn,$_REQUEST['mp_id']));}
$sql="SELECT * FROM `membership_payment` WHERE `mp_id`= $id";
// var_dump($id);die;
 $result=array();
$result1=$conn->query($sql);
if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $result1->fetch_assoc()) 
	{
		$result['result'][]=$row;
	}
} 
else
{
	$result['status']='0';
	$result['error_code']=401;
	$result['error']=custom_http_response_code(401);
	$result['result']=array();
	
}
echo json_encode($result);
$conn->close();
?>