<?php 

require_once 'connection.php';
define('PROJECT_ROOT', dirname(dirname(__FILE__)));
$new_name = "";
$image = $_REQUEST["image"];
$name = $_REQUEST["name"];
$oldfilename=$_REQUEST['name'];
$id=intval(mysqli_real_escape_string($conn,$_REQUEST['id']));
//$path=PROJECT_ROOT."/upload/";
$name = time().'.png';
$path=PROJECT_ROOT."/upload/".$name;
//$path = "D:/xampp/htdocs/gym_master/webroot/upload/".$name;

$file=file_put_contents($path,base64_decode($image));
if($file)
{
	
	//echo $name;
	$get_record="UPDATE `gym_member` SET `image`='".$name."' WHERE `id`='".$id."'";
	$select_query=$conn->query($get_record);

	$result['status']="1";
	//$result['imageName']=$name;
	//$result['imageName']=$image_path.'/'.$name;
	$result['imageName']=$path;
	$result['error_code']=200;
	$result['error']=custom_http_response_code(200);
	
}
else
{
	$result['status']="0";
	$result['imageName']="";
	$result['error_code']=406;
	$result['error']=custom_http_response_code(406);
}
echo json_encode($result);
?>