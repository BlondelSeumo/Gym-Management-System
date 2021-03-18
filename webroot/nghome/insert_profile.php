<?php
include'connection.php';
$id=$_REQUEST['id'];

$username=mysqli_real_escape_string($conn,$_REQUEST['username']);
$first_name=mysqli_real_escape_string($conn,$_REQUEST['first_name']);
$last_name=mysqli_real_escape_string($conn,$_REQUEST['last_name']);
$address=mysqli_real_escape_string($conn,$_REQUEST['address']);
$email=$_REQUEST['email'];
$mobile=mysqli_real_escape_string($conn,$_REQUEST['mobile']);
$birth_date=$_REQUEST['birth_date'];
$password=$_REQUEST['password'];
//$sql="UPDATE `gym_member` SET `username`='$username' WHERE id=10";
if($password=="")
{
	$sql="UPDATE `gym_member` SET `username`='$username',`first_name`='$first_name',`last_name`='$last_name',`address`='$address', 
   `email`='$email',`birth_date`='$birth_date',`mobile`='$mobile' WHERE id=$id";
}
else{
	$password = password_hash($password,PASSWORD_DEFAULT);
	$sql="UPDATE `gym_member` SET `username`='$username',`first_name`='$first_name',`last_name`='$last_name',`address`='$address', 
`email`='$email',`birth_date`='$birth_date',`password`='$password',`mobile`='$mobile' WHERE id=$id";
}

if($conn->query($sql))
{

	$result['status']="1";
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
}
else
{
	$result['status']="0";
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
}
echo json_encode($result);
?>