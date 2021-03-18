<?php
include('connection.php');
function get_membership_paymentstatus($paid_amount,$membership_amount)
{
	if($paid_amount >= $membership_amount)
		return 'Fully Paid';
	elseif($paid_amount== 0 )
		return 'Not Paid';
	else
		return 'Partially Paid';

}

if(isset($_REQUEST['mp_id'])){
	$mp_id=intval(mysqli_real_escape_string($conn,$_REQUEST['mp_id']));
}
$query="SELECT * FROM `membership_payment` WHERE mp_id=$mp_id";
$res=$conn->query($query);
if ($res->num_rows > 0)
{
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
    $row = $res->fetch_assoc();
	$row['gym_logo']=$server_path."gym_master/webroot/img/Thumbnail-img.png";
	$r['invoice'] = $row['mp_id'];
	$date=date_create($row['end_date']);
	$r['due_date']=date('F d,Y');
	$r['amount']=$row['membership_amount'];
	$date=date_create($row['created_date']);
	$r['issue_date']=date_format($date,"F d,Y");
	$r['payment_status']=get_membership_paymentstatus($row['paid_amount'],$row['membership_amount']);

	$query="SELECT `name`,`address`,`gym_logo`,`date_format`,`office_number`,`country`,`currency`
	FROM `general_setting`";
	$res=$conn->query($query);
	if ($res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$result['result']['in_payment']=array_merge($r,$row);
	}
	$query="SELECT `first_name`,`last_name`,`address`,`id`,
	`city`,`state`,`zipcode`,`mobile` FROM `gym_member`
	WHERE `id`=(SELECT `member_id` FROM `membership_payment` WHERE mp_id=$mp_id)";
	$res=$conn->query($query);
	if ($res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$member_id=$row['id'];
		unset($row['id']);
		$result['result']['in_receiver']=$row;
	}
	$query="SELECT `membership_label`,`membership_amount` FROM `membership` WHERE `id`
	=(SELECT `membership_id` FROM `membership_payment` WHERE `mp_id`=$mp_id)";
	$res=$conn->query($query);
	if ($res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$r1['type']=$row['membership_label'];
		$r1['fee']=$row['membership_amount'];
		$r1['total']=$row['membership_amount'];

	}
	$query="SELECT `membership_amount`,`paid_amount` FROM `membership_payment` WHERE `mp_id`=$mp_id";

	$res=$conn->query($query);
	if ($res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$r1['subtotal']=$row['membership_amount'];
		$r1['made_amount']=$row['paid_amount'];
		$r1['due_amount']=$row['membership_amount']-$row['paid_amount'];
		$result['result']['in_membership']=$r1;
	}
	$query="SELECT `paid_by_date`,`payment_method`,`amount` FROM `membership_payment_history` WHERE `mp_id` = $mp_id";
	$res=$conn->query($query);
	if($res->num_rows>0){
		while($row = $res->fetch_assoc())
		{
			$row['paid_by_date'] = date('F d,Y');
			$result['result']['in_payment_history'][]=$row;
		}
	}
	else{$result['result']['in_payment_history']=array();}
}
else
{
	$result['status']='0';
	//$result['error_code']=404;
	//$result['error']=custom_http_response_code(404);
	$result['error_code']=204;
	$result['error']=custom_http_response_code(204);
	$result['result']='';
}
echo json_encode($result);
$conn->close();
?>
