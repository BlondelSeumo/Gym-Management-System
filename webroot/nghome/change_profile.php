<?php

include("connection.php");

$user_id=mysqli_real_escape_string($conn,$_REQUEST['id']);

$result=array();
$cust_select="select * from gym_member where id='$user_id' ";
$get_rec=mysqli_query($sql,$cust_select);

if(mysqli_affected_rows($sql) > 0){
		$user_value=array();
		$update_user = "update gym_member set ";
					if(isset($_REQUEST['username'])){ $username=mysqli_real_escape_string($conn,$_REQUEST['username']); $update_user = $update_user . "username='$username', ";   }

					if(isset($_REQUEST['first_name'])){ $first_name=mysqli_real_escape_string($conn,$_REQUEST['first_name']); $update_user= $update_user . "first_name='$first_name',"; }
					if(isset($_REQUEST['last_name'])){ $last_name=mysqli_real_escape_string($conn,$_REQUEST['last_name']); $update_user= $update_user . "last_name='$last_name',"; }
					if(isset($_REQUEST['address'])){ $address=mysqli_real_escape_string($conn,$_REQUEST['address']); $update_user= $update_user . "address='$address',";}
					if(isset($_REQUEST['email'])){ $email=$_REQUEST['email']; $update_user= $update_user . "email='$email',";}
					if(isset($_REQUEST['mobile'])){ $mobile=$_REQUEST['mobile']; $update_user= $update_user . "mobile='$email',";}
					if(isset($_REQUEST['birth_date'])){$birth_date=(int)$_REQUEST['birth_date']; $update_user= $update_user . "birth_date=$birth_date,";}
					if(isset($_REQUEST['password'])){

							$password=$_REQUEST['password'];
							if($password == ''){
									if($_REQUEST['change_pass'] == 1){
										$update_user = $update_user . "password='',";
									}else if($_REQUEST['change_pass'] == 0){

									}

							}else{
								$update_user = $update_user . "password=md5('$password'),";
							}




					}
					if(isset($_FILES['image']['name'])){
						$path_parts = pathinfo($_FILES['image']['name']);
						$photo_name = 'upload/'.time().'.'.$path_parts['extension'];
						$update_user = $update_user . "image='$photo_name', ";
						move_uploaded_file($_FILES['image']['tmp_name'],'../'.$photo_name);


				}

		$update_user = $update_user . "id =$user_id ";
        $update_user = $update_user . "Where id =$user_id ";



			$run_update=mysqli_query($sql,$update_user);

				if($run_update == TRUE){
						$result['status']='1';
						$result['error_code']=200;
						$result['error']=custom_http_response_code(200);
						$cust_select_data="select * from gym_member where id='$user_id'";

						$get_rec_data=mysqli_query($sql,$cust_select_data);
						while($fetch_data=mysqli_fetch_assoc($get_rec_data)){
							$result['result']=$fetch_data;
							$result['result']['image']=$server_path.$fetch_data['image'];
						}
				}
	}else{
			$result['status']='0';
			$result['error_code']=404;
			$result['error']=custom_http_response_code(404);
	}
	 echo json_encode($result);



?>
