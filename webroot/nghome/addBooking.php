<?php
include('connection.php');

$full_name = $_REQUEST['full_name'];
$gender = $_REQUEST['gender'];
$mobile_no = $_REQUEST['mobile_no'];
$email = $_REQUEST['email'];
$address = $_REQUEST['address'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zipcode = $_REQUEST['zipcode'];
$class_id = $_REQUEST['class_id'];
$booking_date = $_REQUEST['booking_date'];
$booking_type = $_REQUEST['booking_type'];
$booking_amount = isset($_REQUEST['booking_amount'])?$_REQUEST['booking_amount']:'0';
$payment_by = isset($_REQUEST['payment_method'])?$_REQUEST['payment_method']:NULL;
$status = isset($_REQUEST['status'])?$_REQUEST['status']:NULL;


$sql = $conn->query("SELECT * FROM `class_booking` WHERE `email` = '$email' AND `class_id` = '$class_id' AND `booking_type` = 'Demo' ");

if(mysqli_num_rows($sql) > 0 ){
  $result['status']='0';
  $result['error_code']=204;
  $result['error']= "Error ! You can already book this class as demo";

}else{
  
  if($payment_by == 'Stripe'){
    \Stripe\Stripe::setApiKey("sk_test_jS5bcjf5h9MujTWyKoD6DRnq00PbQHHbEZ");

    
  }else if($payment_by == 'Paypal'){

  }else{
    $data['full_name'] = $full_name;
    $data['gender'] = $gender;
    $data['mobile_no'] = $mobile_no;
    $data['email'] = $email;
    $data['address'] = $address;
    $data['city'] = $city;
    $data['state'] = $state;
    $data['zipcode'] = $zipcode;
    $data['class_id'] = $class_id;
    $data['booking_date'] = $booking_date;
    $data['booking_type'] = $booking_type;
    $data['booking_amount'] = $booking_amount;
    $data['transaction_id'] = '';
    $data['payment_by'] = $payment_by;
    $data['status'] = '';
    $data['created_at'] = date('Y-m-d');

    $book = booking($data);

    if($book == '1'){
      $result['status']='1';
      $result['error_code']=200;
      $result['error']=custom_http_response_code(200);
    }else{
      $result['status']='0';
      $result['error_code']=204;
      $result['error']= 'Somethig was wrong';
    }
    
  }
  
}


function booking($data){
  global $conn;

  $sql = "INSERT INTO `class_booking` (`full_name`,`gender`,`mobile_no`,`email`,`address`,`city`,`state`,`zipcode`,`class_id`,`booking_date`,`booking_type`,`booking_amount`,`transaction_id`,`payment_by`,`status`,`created_at`) VALUES ('{$data['full_name']}','{$data['gender']}','{$data['mobile_no']}','{$data['email']}','{$data['address']}','{$data['city']}','{$data['state']}','{$data['zipcode']}','{$data['class_id']}','{$data['booking_date']}','{$data['booking_type']}','{$data['booking_amount']}','{$data['transaction_id']}','{$data['payment_by']}','{$data['status']}','{$data['created_at']}')";

  if($conn->query($sql))
  {
     return $status = '1';
  } else{
     return $status = '0';
  }
}
echo json_encode($result);
?>
