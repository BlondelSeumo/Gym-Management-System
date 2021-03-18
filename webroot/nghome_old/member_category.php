<?php
include('connection.php');
$member_id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));

$sql="SELECT `selected_membership` FROM `gym_member` where `id`=".$member_id." AND role_name='member'";
$res=$conn->query($sql);

$result=array();
// var_dump($res->num_rows);die;
if ($res->num_rows > 0)
{

	$result['status']='1';

	while($row = $res->fetch_assoc())
	{
		$sql2="SELECT `membership_cat_id` FROM `membership` where `id`=".$row['selected_membership'];

		$res2=$conn->query($sql2);

		if ($res2->num_rows > 0)
		{
			while($row2 = $res2->fetch_assoc())
			{
				$sql3="SELECT `name` FROM `category` where `id`=".$row2['membership_cat_id'];

				$res3=$conn->query($sql3);

				if ($res3->num_rows > 0)
				{
					while($row3 = $res3->fetch_assoc())
					{
						$membership_cat_id=$row3['name'];
						$result['result']['category']=$membership_cat_id;
						$result['error_code']=200;
						$result['error']=custom_http_response_code(200);
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
$conn->close();
?>
