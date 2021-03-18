<?php
include('connection.php');

$class_list = $conn->query("SELECT * FROM `class_schedule`");

if($class_list->num_rows > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);

    while($row = $class_list->fetch_assoc())
	{	
		$data['id'] = $row['id'];
		$data['class_name'] = $row['class_name'];
		$data['location'] = $row['location'];
		$data['class_fees'] = getCurrency($row['class_fees']);

		$sql = $conn->query("SELECT `days` FROM `class_schedule_list` WHERE `class_id` = {$data['id']}");
		$res = $sql->fetch_assoc()['days'];
		$book_day = [];

		if(!empty($res)){

			$blocks1 = json_decode($res);

			foreach ($blocks1 as $key => $value) {
				
				$currnt_date = Date('Y-m-d');

				for($i=0;$i<=30;$i++){
					$date = strtotime("+".$i." days", strtotime($currnt_date));

					$day = date('l', $date);

					if($day == $value){
						
						$book_day[] = date('Y-m-d',$date);
					}
				}
				
			}

		}
		$data['days'] = $book_day;
		$result['result'][] = $data; 
	}
	echo json_encode($result);
}else{
	$result['status']='0';
	//$result['error_code']=404;
	$result['error']= "Class list not found";
  	$result['error_code']=204;
}

function getCurrency($fees){
	global $conn;
	$general_setting = $conn->query("SELECT `currency` FROM `general_setting`");
	$cur = $general_setting->fetch_assoc()['currency'];
	
	$currency = $cur.' '.$fees ;

	return $currency;
}
?>