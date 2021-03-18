<?php
include'connection.php';
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
$get_record="SELECT membership.membership_label,membership_payment.membership_amount,
membership_payment.paid_amount,membership_payment.start_date,membership_payment.end_date,
membership_payment.payment_status FROM `membership_payment` INNER JOIN membership ON
membership_payment.membership_id=membership.id WHERE member_id='$id'";
$select_query=$conn->query($get_record);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	while($get_data=mysqli_fetch_assoc($select_query)){
		$get_data['due_amount']=$get_data['membership_amount']-$get_data['paid_amount']."";
	if($get_data['membership_amount']==$get_data['paid_amount'])
		{
			$get_data['payment_status']="Paid";
		}
		else if($get_data['paid_amount']>0 && $get_data['paid_amount']<$get_data['membership_amount'])
		{
			$get_data['payment_status']="Partially Paid";
		}
		else
		{
			$get_data['payment_status']="Not Paid";
		}
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
