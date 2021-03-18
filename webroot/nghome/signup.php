<?php
include("connection.php");
$username=$_REQUEST['username'];
$email=$_REQUEST['email'];
$password=$_REQUEST['password'];
$password = password_hash($password,PASSWORD_DEFAULT);
$member_type='member';
$date = date('Y-m-d');
$result=array();
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql="INSERT INTO `gym_member`(`username`,`email`,`password`,role_name,created_date)
	VALUES ('$username','$email','$password','$member_type','$date');";
    }
    else {
          $sql="INSERT INTO `gym_member`(`username`,`mobile`,`password`,role_name,created_date)
	VALUES ('$username','$email','$password','$member_type','$date');";
    }
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
