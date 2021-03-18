<?php
include'connection.php';
 function get_total_group_members($conn,$gid){

	$get_record="select id,count(*) as count from `gym_member` where `role_name`='member' and `assign_group` like '%$gid%'";
	return mysqli_fetch_assoc(mysqli_query($conn,$get_record))['count'];

	}



 $data_group ="select * from gym_group";
	$group_result=mysqli_query($conn,$data_group);
	$temp=array();
	while($get_row=mysqli_fetch_assoc($group_result)){
		$get_row['image']=$image_path.$get_row['image'];
		$get_row['count']= get_total_group_members($conn,$get_row['id']);
		//member record

		//var_dump($result);

			$member=array();
			$gp_id=$get_row['id'];
			$get_record_member="SELECT `image`,`first_name`,`last_name` FROM `gym_member` WHERE `role_name`='member' and assign_group LIKE '%$gp_id%'";
			$rs=mysqli_query($conn,$get_record_member);
			while($get_rows_member=mysqli_fetch_assoc($rs)){
					$get_rows_member['image']=$image_path.$get_rows_member['image'];
					//$member[]=;
					$member[]=$get_rows_member;
			}

			//var_dump($member);
		$get_row['member']=$member;
		$result['status']='1';
		$result['error_code']=200;
		$result['error']=custom_http_response_code(200);
		$result['result'][]=$get_row;
		//member and record


	}
 echo json_encode($result);

 die;



$get_record="SELECT gym_group.image,gym_group.name,(COUNT(*))AS `count` FROM `gym_group`
INNER JOIN gym_member WHERE gym_member.role_name='member'
AND gym_member.assign_group LIKE '%1%'";
$select_query=$conn->query($get_record);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error']='';
	while($get_data=mysqli_fetch_assoc($select_query)){
		$get_data['image']=$server_path."GYM/gym_master/webroot/upload/".$get_data['image'];
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
