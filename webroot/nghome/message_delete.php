<?php
include("connection.php");
if(isset($_REQUEST['id']))
{
	$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
}
$result = array();
$arr=explode(',',$id);
for($i=0;$i<sizeof($arr);$i++)
{
	deleteMsg($arr[$i]);
}
function deleteMsg($id)
{
	global $result,$conn;
	$sql="DELETE FROM `gym_message` WHERE `id`= $id";
	if($conn->query($sql)) 
	{
		$result['status']='1';
		$result['error_code']=200;
		$result['error']=custom_http_response_code(200);
	} 
	else
	{
		$result['status']='0';
		$result['error_code']=400;		
		$result['error']=custom_http_response_code(400);
	}
}
echo json_encode($result);
$conn->close();

?>