<?php
include('connection.php');
$sql="SELECT `id`, `name` FROM `category";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $res->fetch_assoc()) 
	{	
		$sql="SELECT `id`, `title` FROM `activity` WHERE `cat_id`='".$row['id']."'";
		$res1=$conn->query($sql);
		if ($res1->num_rows > 0) {
			while($r = $res1->fetch_assoc()) 
			{
				$r['cat_id']=$row['id'];
				$result['result'][]=$r;
			}
		}
		else
		{

		}
	}
}
else
{
	$result['status']='0';
	$result['error']='No Activity Found!';
	$result['result']=array();
}
echo json_encode($result);
?>