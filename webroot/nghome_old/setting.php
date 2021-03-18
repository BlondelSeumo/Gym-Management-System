<?php
include("connection.php");
$sql="SELECT `name`, `start_year`, `address`, `office_number`, `country`,
 `email`, `weight`, `height`, `chest`, `waist`, `thing`, `arms`, `fat`,
`paypal_email`, `currency`, `left_header`  FROM `general_setting`";
$res=$conn->query($sql);
$result=array();
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($res))
	{
			$result['result']=$get_data;
	}
}
else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
  $result['error_code']=204;
	$result['error']=custom_http_response_code(204);
}
echo json_encode($result);
?>
