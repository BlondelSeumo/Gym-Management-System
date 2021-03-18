<?php
include('connection.php');

$sql="SELECT `menu` FROM `gym_accessright` where member=1";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $res->fetch_assoc()) 
	{
		$row['menu']=trim($row['menu']);
		//$row['menu_title'] = $row['menu_title'];
		//$row['menu_image'] = $server_path.'webroot/img/icon/'.$row['menu_icon'];
		$result['result']['accessright'][]=$row;
	}
}
else
{
	$result['status']='0';
	$result['error_code']=400;
	$result['error']=custom_http_response_code(400);
	$result['result']=array();
}
echo json_encode($result);
?>