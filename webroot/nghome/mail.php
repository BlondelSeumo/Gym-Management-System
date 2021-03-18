<?php
include("connection.php");
$sql="SELECT `name`,`email` FROM `general_setting` LIMIT 1";
$sql = mysqli_real_escape_string($conn,$sql);
$r=$conn->query($sql);
if($r != false)
{
	if($r->num_rows > 0)
	{
		$res = $r->fetch_assoc();
		$sys_email =$res['email'];
		$sys_name = $res['name'];
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: '.$sys_name.' <'.$sys_email.'>' . "\r\n";
		$message = "<p>Hi Gaurang,</p>";
		$message .= "<p>Thank you for registering on our system.</p>";
		$message .= "<p>Your Username:Vyas13</p>";
		$message .= "<p>You can login once after admin review your account and activates it.</p>";
		$message .= "<p>Thank You.</p>";
		@mail("gaurang111@dasinfomedia.com",_("New Registration : {$sys_name}"),$message,$headers);
		
	}
}
?>
