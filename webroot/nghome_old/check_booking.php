<?php
include('connection.php');

$date = $_REQUEST['date'];
$class_id = $_REQUEST['class_id'];

if($date != '' && $class_id != ''){
  $count = 0;
  $sql = "SELECT class_id,booking_day,booking_date FROM class_booking WHERE booking_date = '$date' AND class_id = $class_id";
  $res = $conn->query($sql);

  if($res->num_rows >0){
    $result['status']='1';
    $result['error_code']=200;
    $result['error']=custom_http_response_code(200);
    while($r=$res->fetch_assoc()){
       $count++;
      $r['class_name'] = class_name($r['class_id']);
      $r['total_member'] = (int)total_member($r['class_id']);
      $r['booked_class'] = $count;
      $r['available_class'] = (int)$r['total_member'] - $r['booked_class'];
      $result['class'] = $r;
    }
    //$result['class'][] = $result['class'];
  }
}else{
  $result['status']='0';
  $result['error_code']=204;
  $result['error']=custom_http_response_code(204);
  $result['result']=array();
}

function total_member($class_id){
  global $conn;
  $sql = $conn->query("SELECT max_member FROM class_schedule WHERE id=$class_id");
  return $sql->fetch_assoc()['max_member'];
}
function class_name($class_id){
  global $conn;
  $sql = $conn->query("SELECT class_name FROM class_schedule WHERE id=$class_id");
  return $sql->fetch_assoc()['class_name'];
}
echo json_encode($result);
?>
