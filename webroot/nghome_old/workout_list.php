<?php
include('connection.php');
if(isset($_REQUEST['id'])){$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));}
$query="SELECT `record_date` FROM `gym_daily_workout` WHERE `member_id`=6";
$res=$conn->query($query);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);

    while($row = $res->fetch_assoc())
	{
		$d = date_parse_from_format("Y-m-d", $row['record_date']);

		//$result[$d['year']][$d['month']][][$d['day']]=$d['month'];.
		$dateObj   = DateTime::createFromFormat('!m', $d['month']);
		$monthName = $dateObj->format('F');
		$month[]=$d['year'].' '.$monthName;
		//$result[$d['year'].' '.$monthName][]=$d['day'];

	}
	$month=array_unique($month);
	$result['result']=array_values($month);

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
//echo $result['2016 August'][0];
echo json_encode($result);
$conn->close();
?>
