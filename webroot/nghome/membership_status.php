<?php
include('connection.php');

$user_id = $_REQUEST['id'];
$day = $_REQUEST['day'];
//$email = $_REQUEST['email'];

$result = array();
$sql = "SELECT `id`,`first_name`,`last_name`,`membership_status`,`membership_valid_from`,`membership_valid_to`,`image`,`mobile` FROM `gym_member` WHERE `id` = $user_id";
$data = $conn->query($sql);

if(mysqli_num_rows($data) > 0){
   
   
    while($details = mysqli_fetch_assoc($data))
	{ 
			
		if($details['membership_valid_to'] >= date('Y-m-d') && $details['membership_valid_from'] <= date('Y-m-d'))
		{
           
		    $result['status'] = '1';
			$result['error_code'] = 200;
			$result['error'] = custom_http_response_code(200);
			
			$result['result']['detail'] = $details;
			$result['result']['detail']['membership_valid_from'] = date('d-m-Y',strtotime($details['membership_valid_from']));
			$result['result']['detail']['membership_valid_to'] = date('d-m-Y',strtotime($details['membership_valid_to']));
			$result['result']['detail']["image"]=$image_pa.$details['image'];
		   
            $sql = "SELECT c.id,c.class_name,c.days FROM gym_member_class m LEFT JOIN class_schedule c ON  m.assign_class = c.id WHERE m.member_id = '".$user_id."'";
            $fetch_data = $conn->query($sql);
			$i = 0;
            if(mysqli_num_rows($fetch_data) >0)
            {
               
                while($row = mysqli_fetch_assoc($fetch_data)){
                   $days_array = json_decode($row['days']);
                   
					if($days_array != NULL){
						if(in_array($day,$days_array))
						{
							$result['result']["class"][] =  $row ;
							$i++;
						}
						if($i== 0){
							 $result['result']["class"] = array();
						}
					}
                     else{
                        $result['result']["class"] = array();
                    }  
                }
                
            } 
        }
		else{
			$result['status'] = '0';
			$result['error_code'] = 400;
			$result['error'] = custom_http_response_code(400);
		}
    }
}else{
    $result['status'] = '0';
    $result['error_code'] = 400;
    $result['error'] = custom_http_response_code(400);
}
echo json_encode($result);
$conn->close();
?>