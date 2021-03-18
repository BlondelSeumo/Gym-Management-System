<?php
include('connection.php');
if(isset($_REQUEST['measurement'])){$measurement=mysqli_real_escape_string($conn,$_REQUEST['measurement']);}
if(isset($_REQUEST['result'])){$result=mysqli_real_escape_string($conn,$_REQUEST['result']);}
if(isset($_REQUEST['user_id'])){$user_id=intval(mysqli_real_escape_string($conn,$_REQUEST['user_id']));}
if(isset($_REQUEST['date'])){$date=$_REQUEST['date'];}
//if(isset($_REQUEST['image'])){$image=$_REQUEST['image'];}
if(isset($_REQUEST['created_by'])){$created_by=mysqli_real_escape_string($conn,$_REQUEST['created_by']);}
$d=date_create($date);
$date=date_format($d,"Y-m-d");
$created_date = date('Y-m-d');
$sql="INSERT INTO `gym_measurement`
(`result_measurment`, `result`, `user_id`, `result_date`, `image`, `created_by`, `created_date`)
 VALUES ('$measurement',$result,$user_id,'$date','measurement.png',$created_by,'$created_date')";
$result=array();
if ($conn->query($sql)) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
} 
else
{
	$result['status']='0';
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
}
echo json_encode($result);
$conn->close();
?>