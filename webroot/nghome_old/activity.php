<?php
include('connection.php');
$sql="SELECT `id`, `name` FROM `category`";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    while($row = $res->fetch_assoc())
	{
		$sql="SELECT `id`, `title` FROM `activity` WHERE `cat_id`='".$row['id']."'";
		$res1=$conn->query($sql);
		if ($res1->num_rows > 0) {
			while($r = $res1->fetch_assoc())
			{
				$r['cat_id']=$row['id'];
				$result['result'][]=$r;
			}
		}
	}
}
else
{
	$result['status']='0';
	// $result['error_code']=404;
	// $result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']=array();
}
echo json_encode($result);
?>
