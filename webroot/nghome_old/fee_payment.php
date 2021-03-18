<?php
include("connection.php");
include("getCurrency.php");

if (isset($_REQUEST['id'])){
	$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
}

$sql="SELECT * FROM `membership_payment` WHERE `member_id`= $id";
$result=array();
$result1=$conn->query($sql);

if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);

    while($row = $result1->fetch_assoc()) {
		$membership=$row['membership_id'];
		$sql="SELECT * FROM `membership` WHERE `id`=$membership";
		$row['membership']=$conn->query($sql)->fetch_assoc()['membership_label'];
		$date=date_create($row['start_date']);
		$row['start_date']=date_format($date,"F d,Y");
		$date=date_create($row['end_date']);
		$row['end_date']=date_format($date,"F d,Y");
		$row['due_amount']=$row['membership_amount']-$row['paid_amount'];
		$row['membership_amount']=$row['membership_amount'];
		$member_id=$row['member_id'];
		$sql="SELECT `first_name`,`last_name` FROM `gym_member` WHERE `id`=$member_id";

		if($row['membership_amount']==$row['paid_amount'])
		{
			$row['payment_status']="Paid";
		}
		else if($row['paid_amount']>0 && $row['paid_amount']<$row['membership_amount'])
		{
			$row['payment_status']="Partially Paid";
		}
		else
		{
			$row['payment_status']="Not Paid";
		}
		$r=$conn->query($sql)->fetch_assoc();
		$row['member_name']=$r['first_name']." ".$r['last_name'];
		$result['result'][]=$row;
	}
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
echo json_encode($result);
$conn->close();
?>
