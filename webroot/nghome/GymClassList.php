<?php
include("connection.php");
$sql="SELECT `id`,`class_name` FROM `class_schedule`";
$res=$conn->query($sql);
$result=array();
if ($res->num_rows > 0)
{
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($row = $res->fetch_assoc())
	{
		$result['result']['classes'][]=$row;
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
