<?php
include'connection.php';
$get_record="SELECT gym_reservation.event_name,gym_reservation.event_date,
gym_event_place.place,gym_reservation.start_time,gym_reservation.end_time FROM `gym_reservation`
LEFT JOIN gym_event_place ON gym_event_place.id=gym_reservation.place_id";
$select_query=$conn->query($get_record);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query))
	{
		$get_data['start_time']=substr_replace($get_data['start_time'], ':', -3, -2);
		$get_data['end_time']=substr_replace($get_data['end_time'], ':', -3, -2);
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
