<?php 
include('connection.php');

$user_id = $_REQUEST['member_id'];
$class_id = $_REQUEST['class_id'];
$attendance_date = date('Y-m-d');
$status = "Present";
$attendance_by = $_REQUEST['staff_id'];
$role_name = "member";
//$scan_by = $_REQUEST['staff_id'];
//$scan_date = date('Y-m-d');

if($user_id != NULL && $class_id != NULL && $attendance_by != NULL )
{
	$sql1 = "SELECT `id` ,`activated`, `membership_valid_from` , `membership_valid_to` from `gym_member` WHERE `id` = '".$user_id."' AND `membership_valid_from` <= '".$attendance_date."' AND `membership_valid_to` >= '".$attendance_date."' AND `activated` = 1";
	$data = $conn->query($sql1);
	
	if(mysqli_num_rows($data) >= 1)
	{
		$sql = "SELECT `user_id` , `attendance_date` FROM `gym_attendance` WHERE `user_id` = '".$user_id."' AND `attendance_date` = '".$attendance_date."' AND `class_id` = '".$class_id."' ";
		$resl = $conn->query($sql);
		
		if(mysqli_num_rows($resl) >= 1 )
		{
			$result['status']='0';
			$result['error_code']=409;
			$result['error']= " Your Attendance Already Taken.";
		}
		else
		{
			$sql = "INSERT INTO gym_attendance (`user_id`,`class_id`,`attendance_date`,`status`,`attendance_by`,`role_name`) VALUES ('".$user_id."','".$class_id."','".$attendance_date."','".$status."','".$attendance_by."','".$role_name."')";
			
			$res = $conn->query($sql);

			if($res)
			{
				$result['status'] = '1';
				$result['error_code'] =  200;
				$result['error'] = custom_http_response_code(200);
			}
			else
			{
				$result['status']='0';
				$result['error_code']=404;
				$result['error']=custom_http_response_code(404);
			}
		}
	}
	else
	{
		$result['status']='0';
		//$result['error_code']=400;
		//$result['error']=custom_http_response_code(400);
		$result['error'] = "This Account Is Expired";
	}	
}
else
{
	$result['status']='0';
	$result['error_code']=400;
	$result['error']=custom_http_response_code(400);
}
echo json_encode($result);
$conn->close();
?>