<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$get_record="SELECT gym_member.image,gym_member.member_id,gym_member.first_name,gym_member.address,
gym_member.email,gym_member.mobile,gym_member.gender,gym_member.birth_date,gym_member.username,
membership.membership_label,gym_member.membership_status,gym_member.membership_valid_to,
gym_member.intrested_area,gym_group.name FROM `gym_member` INNER JOIN membership ON membership.id=gym_member.selected_membership
INNER JOIN gym_group ON gym_group.id=gym_member.activated WHERE gym_member.id='$id'";
$get_rec="SELECT class_schedule.class_name FROM `class_schedule`
INNER JOIN gym_member_class ON gym_member_class.assign_class=class_schedule.id where gym_member_class.member_id='$id'";
//$get_staff="SELECT gym_member.first_name,gym_member.last_name FROM `gym_member` WHERE `id`='$id'";
$a="SELECT assign_staff_mem FROM `gym_member` where `id`='$id' ";
$idd=mysqli_fetch_assoc(mysqli_query($conn,$a))['assign_staff_mem'];
$get_staff = "SELECT first_name,last_name FROM `gym_member` where `id` = '$idd' ";

$intreast="SELECT `intrested_area` FROM `gym_member` WHERE `id`='$id'";
$intr=mysqli_fetch_assoc(mysqli_query($conn,$intreast))['intrested_area'];
$get_intreast="SELECT `interest` FROM `gym_interest_area` WHERE `id`='$intr'";

$select_intreast=$conn->query($get_intreast);
$select_staff=$conn->query($get_staff);
$select_querys=$conn->query($get_rec);
$select_query=$conn->query($get_record);
$result=array();
$staff_member="";
$staff_intreast="";

if(mysqli_num_rows($select_intreast) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	$staff_intreast="";
	while($get_data=mysqli_fetch_assoc($select_intreast)){
		//$get_data['image']=$server_path."GYM/gym_master/webroot/upload/".$get_data['image'];
		//$class=$class.$get_data;
		$staff_intreast.=$get_data['interest']."";
	}
}else
{
	$result['status']='0';
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
	$result['result']=array();
}

if(mysqli_num_rows($select_staff) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	$staff_member="";
	while($get_data=mysqli_fetch_assoc($select_staff)){
		//$get_data['image']=$server_path."GYM/gym_master/webroot/upload/".$get_data['image'];
		//$class=$class.$get_data;
		$staff_member.=$get_data['first_name']." ";
		$staff_member.=$get_data['last_name']."";
	}
}else
{
	$result['status']='0';
	$result['error_code']=404;
	$result['error']=custom_http_response_code(404);
	$result['result']=array();
}

if(mysqli_num_rows($select_querys) > 0){
	$result['status']='1';
	$result['error']='';
	$class="";
	while($get_data=mysqli_fetch_assoc($select_querys)){
		//$get_data['image']=$server_path."GYM/gym_master/webroot/upload/".$get_data['image'];
		//$class=$class.$get_data;
		$class.=$get_data['class_name'].",";
	}
}else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']=array();
}


if(mysqli_num_rows($select_query) > 0 ){

	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query)){
		$get_data['image']=$server_path.$image_pa.$get_data['image'];
		$get_data['class']=rtrim($class,",");
		$get_data['staff_member']=rtrim($staff_member,"");
		$get_data['intrested_area']=rtrim($staff_intreast,"");
		$result['result'][]=$get_data;


	}
}else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']=array();
}

echo json_encode($result);
?>
