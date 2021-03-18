<?php
include('connection.php');
$sql="SELECT `id`, `level` FROM `gym_levels`";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $res->fetch_assoc())
	{
		$result['result']['levels'][]=$row;
	}
}
else
{
	$result['status']='1';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']=array();
}
echo json_encode($result);
?>
