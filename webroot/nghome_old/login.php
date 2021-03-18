<?php
include'connection.php';
if(isset($_REQUEST['username'])){$uname=mysqli_real_escape_string($conn,$_REQUEST['username']);}
if(isset($_REQUEST['password'])){$password=mysqli_real_escape_string($conn,$_REQUEST['password']);}
if(isset($_REQUEST['token'])){$token=mysqli_real_escape_string($conn,$_REQUEST['token']);}

//$get_record="SELECT * FROM `gym_member` WHERE `username` = '$uname' AND `role_name` = 'member' " ;
$get_record="SELECT * FROM `gym_member` WHERE `username` = '$uname' " ;
$Select_query=$conn->query($get_record);

$error = 1;	
$result=array();
if(mysqli_num_rows($Select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($Select_query))
	{
		
		$hash = $get_data['password'];
		//$get_data['Image']=$image_path.$get_data['image'];
		//$result['result'][]=$get_data;
		
		if(password_verify($password,$hash))
		{
			
			// $hash = $get_data['password'];
			$TokenUpdate = "update gym_member set token = '$token' where username = '$uname'";
			$TokenUpdateExecute = $conn->query($TokenUpdate);
				
			$get_data['Image']=$image_path.$get_data['image'];
			$result['result'][]=$get_data;	
			$error = 0;
			
			if($get_data['role_name']=='member') {
				if($get_data['activated']==0) {
					$result['status']='0';
					$result['error_code']=401;
					//$result['error']=custom_http_response_code(401);
					$result['error']="You are not active now";
					$result['result']=array();
					$error=2;
				}elseif($get_data['membership_valid_to'] < date('Y-m-d')) {
					$result['status']='0';
					$result['error_code']=401;
					$result['error']="Your membership are expired";
					$result['result']=array();
					$error=2;
				}else {		
					$todayDate = date('Y-m-d');
					// $todayDate = '2018-11-15';
					$GymDailyWorkout = "select id,member_id,record_date,reminder_status from gym_daily_workout where record_date='".$todayDate."' and reminder_status = 0";
					
					$DailyWorkoutData = $conn->query($GymDailyWorkout);
					
					if(mysqli_num_rows($DailyWorkoutData) > 0) {
						while($dailydata=mysqli_fetch_assoc($DailyWorkoutData)) {
							
							$id = $dailydata['id'];
							$member_id = $dailydata['member_id'];
							$reminder_status = $dailydata['reminder_status'];
							
							SendNotification($token,'Workout Reminder!','Today Your workout is assigned.',1,'title','workout reminder',$get_data['device_type']);
							
							$GymDailyWorkoutUpdate = "update gym_daily_workout set reminder_status = 1 where id=".$id;
							$GymDailyWorkoutUpdateExecute = $conn->query($GymDailyWorkoutUpdate);
							
						}
					}
				}
			}
		}
	}	
}

if($error == 1 )
{	
	$result['status']='0';
	$result['error_code']=401;
	$result['error']=custom_http_response_code(401);
	$result['result']=array();
}
echo json_encode($result);
?>