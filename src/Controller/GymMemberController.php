<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\Network\Session\DatabaseSession;
use Cake\Datasource\ConnectionManager;
use Cake\Database\Connection;
use Cake\Mailer\Email;
use Cake\I18n\Time;


Class GymMemberController extends AppController
{
	public function initialize()
	{
		parent::initialize();

		$this->loadComponent("GYMFunction");
		require_once(ROOT . DS .'vendor' . DS  . 'chart' . DS . 'GoogleCharts.class.php');
		$session = $this->request->session()->read("User");
		$this->set("session",$session);		
	}
	
	public function memberList()
	{
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator")
		{
			$data = $this->GymMember->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
			//debug($data);die;
		}
		else if($session["role_name"] == "member"){
			$uid = intval($session["id"]);
			if($this->GYMFunction->getSettings("member_can_view_other"))
			{
				$data = $this->GymMember->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
			}else{
				$data = $this->GymMember->find("all")->where(["id"=>$uid])->hydrate(false)->toArray();
			}
			
		}
		else if($session["role_name"] == "staff_member"){
			$uid = intval($session["id"]);
			if($this->GYMFunction->getSettings("staff_can_view_own_member"))
			{
				$data = $this->GymMember->find("all")->where(["assign_staff_mem"=>$uid])->hydrate(false)->toArray();
			}else{
				$data = $this->GymMember->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
			}
		}
		else{
			$data = $this->GymMember->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
		}
		
		
		$this->set("data",$data);	
	}
	
	public function addMember()
	{	
		$this->set("edit",false);
		$this->set("title",__("Add Member"));
		
		$lastid = $this->GymMember->find("all",["fields"=>"id"])->last();
		$lastid = ($lastid != null) ? $lastid->id + 1 : 01 ;
		
		$member = $this->GymMember->newEntity();
		$m = date("d");
		$y = date("y");
		$prefix = "M".$lastid;
		$member_id = $prefix.$m.$y;
		
		$this->set("member_id",$member_id);
		$staff = $this->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"staff_member"]);
		$staff = $staff->select(["id","name"=>$staff->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$classes = $this->GymMember->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
		$groups = $this->GymMember->GymGroup->find("list",["keyField"=>"id","valueField"=>"name"]);
		$interest = $this->GymMember->GymInterestArea->find("list",["keyField"=>"id","valueField"=>"interest"]);
		$source = $this->GymMember->GymSource->find("list",["keyField"=>"id","valueField"=>"source_name"]);
		$membership = $this->GymMember->Membership->find("list",["keyField"=>"id","valueField"=>"membership_label"]);
	
		$this->set("staff",$staff);
		$this->set("classes",$classes);
		$this->set("groups",$groups);
		$this->set("interest",$interest);
		$this->set("source",$source);
		$this->set("membership",$membership);
		$this->set("referrer_by",$staff);
		
		if($this->request->is("post"))
		{
			
			$ext = $this->GYMFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				
				$this->request->data['member_id'] = $member_id;
				$image = $this->GYMFunction->uploadImage($this->request->data['image']);
				$this->request->data['image'] = (!empty($image)) ? $image : "Thumbnail-img.png";
				$this->request->data['birth_date'] = $this->GYMFunction->get_db_format_date($this->request->data['birth_date']);
				//$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
				//$this->request->data['inquiry_date'] = date("Y-m-d",strtotime($this->request->data['inquiry_date']));
				$this->request->data['inquiry_date'] = $this->GYMFunction->get_db_format_date($this->request->data['inquiry_date']);
				$this->request->data['trial_end_date'] = $this->GYMFunction->get_db_format_date($this->request->data['trial_end_date']);
				//$this->request->data['trial_end_date'] = date("Y-m-d",strtotime($this->request->data['trial_end_date']));
				if(isset($this->request->data['membership_valid_from']))
				{
					//$this->request->data['membership_valid_from'] = date("Y-m-d",strtotime($this->request->data['membership_valid_from']));
					$this->request->data['membership_valid_from'] = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_from']);
				
				}
				if(isset($this->request->data['membership_valid_to']))
				{
					//$this->request->data['membership_valid_to'] = date("Y-m-d",strtotime($this->request->data['membership_valid_to']));
					$this->request->data['membership_valid_to'] = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_to']);
				}
				//$this->request->data['first_pay_date'] = date("Y-m-d",strtotime($this->request->data['first_pay_date']));
				if($this->request->data['first_pay_date'] != '')
				{
					$this->request->data['first_pay_date'] = $this->GYMFunction->get_db_format_date($this->request->data['first_pay_date']);
				}
				
				$this->request->data['created_date'] = date("Y-m-d");
				$this->request->data['assign_group'] = json_encode($this->request->data['assign_group']);
				switch($this->request->data['member_type'])
				{
					CASE "Member":
						$this->request->data['membership_status'] = "Continue";
					break;
					CASE "Prospect":
						$this->request->data['membership_status'] = "Not Available";
					break;
					CASE "Alumni":
						$this->request->data['membership_status'] = "Expired";
					break;
					
				}
				$this->request->data["role_name"]="member";
				$this->request->data["activated"]= 1;
				$member = $this->GymMember->patchEntity($member,$this->request->data);
				
				if($this->GymMember->save($member))
				{
					$this->request->data['member_id'] = $member->id;
					$this->GYMFunction->add_membership_history($this->request->data);
					if($this->addPaymentHistory($this->request->data))
					{
						$this->Flash->success(__("Success! Record Saved Successfully."));					
					}
					
					foreach($this->request->data["assign_class"] as $class)
					{
						$new_row = $this->GymMember->GymMemberClass->newEntity();
						$data = array();
						$data["member_id"] = $member->id;
						$data["assign_class"] = $class;
						$new_row = $this->GymMember->GymMemberClass->patchEntity($new_row,$data);
						$this->GymMember->GymMemberClass->save($new_row);
					}
				}else
				{				
					if($member->errors())
					{	
						foreach($member->errors() as $error)
						{
							foreach($error as $key=>$value)
							{
								$this->Flash->error(__($value));
							}						
						}
					}
				}	
				return $this->redirect(["action"=>"memberList"]);
			}else{
					$this->Flash->error(__("Invalid File Extension, Please Retry."));
					return $this->redirect(["action"=>"add-member"]);
			}
		}		
	}
	
	
	public function addPaymentHistory($data)
	{
		$row = $this->GymMember->MembershipPayment->newEntity();
		$save["member_id"] = $data["member_id"];
		$save["membership_id"] = $data["selected_membership"];
		$save["membership_amount"] = $this->GYMFunction->get_membership_amount($data["selected_membership"]);
		$save["paid_amount"] = 0;
		$save["start_date"] = $data["membership_valid_from"];
		$save["end_date"] = $data["membership_valid_to"];
		$save["membership_status"] = $data["membership_status"];
		$save["payment_status"] = 0;
		$save["created_date"] = date("Y-m-d");
		$save["created_by"] = 1;
		$row = $this->GymMember->MembershipPayment->patchEntity($row,$save);
		if($this->GymMember->MembershipPayment->save($row))
		{return true;}else{return false;}
	}
	
	public function editMember($id)
	{
		$this->set("edit",true);
		$this->set("title",__("Edit Member"));
		$this->set("eid",$id);
		$membership_classes_id=array();
		$session = $this->request->session()->read("User");
		$data = $this->GymMember->get($id)->toArray();
		
		
		
		$membership_classes = $this->GymMember->Membership->find()->where(["id"=>$data['selected_membership']])->select(["membership_class"])->hydrate(false)->toArray();		
			
		//$membership_classes = (json_decode($membership_classes[0]["membership_class"])); /*ERROR IN NEW PHP 5.7 VERSION */
		/* if(!empty($membership_classes)) FOR PHP 5.7 But NOT WORKNIG
		{
			$membership_classes = $membership_classes[0]["membership_class"];
			$membership_classes = str_ireplace(array("[","]","'"),"",$membership_classes);
			$membership_classes = explode(",",$membership_classes);	
			$classes = $this->GymMember->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->where(["id IN"=>$membership_classes])->toArray();

		}
		else{
			$classes = array();
		} */
		foreach($membership_classes as $value){
			
			$membership_classes_id[]=$value['membership_class'];
		}
		
		if(!empty($membership_classes_id)) 
		{
			$classes = $this->GymMember->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->where(["id IN"=>$membership_classes_id])->toArray();
		}else{
			$classes = array();
		} 
		
		$member_classes = $this->GymMember->GymMemberClass->find()->where(["member_id"=>$id])->select(["assign_class"])->order(['id' => 'ASC'])->hydrate(false)->toArray();
		$mem_classes = array();
		foreach($member_classes as $mc)
		{
			$mem_classes[] = $mc["assign_class"];
		}
		
		$this->set("member_class",$mem_classes);
		if($session["id"] != $data["id"] && $session["role_name"] != 'administrator')
		{
			echo $this->Flash->error("No sneaking around! ;( ");
			return $this->redirect(["action"=>"memberList"]);			
		}
	
		$this->set("data",$data);		
		$staff = $this->GymMember->find("list",["keyField"=>"id","valueField"=>["name"]])->where(["role_name"=>"staff_member"]);
		$staff = $staff->select(["id","name"=>$staff->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		
		$groups = $this->GymMember->GymGroup->find("list",["keyField"=>"id","valueField"=>"name"]);
		$interest = $this->GymMember->GymInterestArea->find("list",["keyField"=>"id","valueField"=>"interest"]);
		$source = $this->GymMember->GymSource->find("list",["keyField"=>"id","valueField"=>"source_name"]);
		$membership = $this->GymMember->Membership->find("list",["keyField"=>"id","valueField"=>"membership_label"]);
		
		$this->set("staff",$staff);
		$this->set("classes",$classes);
		$this->set("groups",$groups);
		$this->set("interest",$interest);
		$this->set("source",$source);
		$this->set("membership",$membership);
		$this->set("referrer_by",$staff);
				
		$this->render("addMember");		

		if($this->request->is("post"))
		{
			$row = $this->GymMember->get($id);
			//var_dump($this->request->data['birth_date']);die;
			$ext = $this->GYMFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$image = $this->GYMFunction->uploadImage($this->request->data['image']);
				if($image != "")
				{
					$this->request->data['image'] = $image;
					unlink(WWW_ROOT."/upload/".$data['image']);
				}else{
					unset($this->request->data['image']);

				}
				/* $this->request->data['image'] = $image ; */

				$this->request->data['birth_date'] = $this->GYMFunction->get_db_format_date($this->request->data['birth_date']); 
			
				//$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
				
				//$this->request->data['inquiry_date'] = (($this->request->data['inquiry_date'] != '')?date("Y-m-d",strtotime($this->request->data['inquiry_date'])):'');
				$this->request->data['inquiry_date'] = (($this->request->data['inquiry_date'] != '')?$this->GYMFunction->get_db_format_date($this->request->data['inquiry_date']):'');
				
				//echo $this->request->data['inquiry_date']; die;
				$this->request->data['trial_end_date'] = (($this->request->data['trial_end_date'] != '')?$this->GYMFunction->get_db_format_date($this->request->data['trial_end_date']):'');
				if(isset($this->request->data['membership_valid_from']))
				{
					//echo $this->request->data['membership_valid_from'] = date("Y-m-d",strtotime($this->request->data['membership_valid_from'])); die;
					$this->request->data['membership_valid_from'] = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_from']);

					$this->request->data['alert_sent'] = 0;
					$this->request->data['admin_alert'] = 0;
				}
				if(isset($this->request->data['membership_valid_to']))
				{
					//$this->request->data['membership_valid_to'] = date("Y-m-d",strtotime($this->request->data['membership_valid_to']));
					$this->request->data['membership_valid_to'] = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_to']);
				}
				//$this->request->data['first_pay_date'] = date("Y-m-d",strtotime($this->request->data['first_pay_date']));
				if($this->request->data['first_pay_date'] != '')
				{
					$this->request->data['first_pay_date'] = $this->GYMFunction->get_db_format_date($this->request->data['first_pay_date']);
				}
				
				$this->request->data['assign_group'] = json_encode($this->request->data['assign_group']);
				
				$update = $this->GymMember->patchEntity($row,$this->request->data);
				
				if($this->GymMember->save($update))
				{
					$this->Flash->success(__("Success! Record Updated Successfully."));
					$this->GymMember->GymMemberClass->deleteAll(["member_id"=>$id]);
					foreach($this->request->data["assign_class"] as $class)
					{
						$data = array();
						$new_row = $this->GymMember->GymMemberClass->newEntity();
						$data["member_id"] = $id;
						$data["assign_class"] = $class;
						//var_dump($data);
						$new_row = $this->GymMember->GymMemberClass->patchEntity($new_row,$data);
						$this->GymMember->GymMemberClass->save($new_row);

						//debug($data);
					}
					//die;
					return $this->redirect(["action"=>"memberList"]);
				}
				else
				{				
					if($update->errors())
					{	
						foreach($update->errors() as $error)
						{
							foreach($error as $key=>$value)
							{
								$this->Flash->error(__($value));
							}						
						}
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"editMember",$id]);
			}
		}
		
	}
	
	public function deleteMember($id) {
		$row = $this->GymMember->get($id);
		if($this->GymMember->delete($row)) {
			$this->Flash->success(__("Success! Record Deleted Successfully."));
			return $this->redirect($this->referer());
		}		
	}
	
	public function viewMember($id)
	{
		$weight_data["data"] = $this->GYMFunction->generate_chart("Weight",$id);
		$weight_data["option"] = $this->GYMFunction->report_option("Weight");
		$this->set("weight_data",$weight_data);
		
		$height_data["data"] = $this->GYMFunction->generate_chart("Height",$id);
		$height_data["option"] = $this->GYMFunction->report_option("Height");
		$this->set("height_data",$height_data);		
		
		$thigh_data["data"] = $this->GYMFunction->generate_chart("Thigh",$id);
		$thigh_data["option"] = $this->GYMFunction->report_option("Thigh");
		$this->set("thigh_data",$thigh_data);
		
		$chest_data["data"] = $this->GYMFunction->generate_chart("Chest",$id);
		$chest_data["option"] = $this->GYMFunction->report_option("Chest");
		$this->set("chest_data",$chest_data);
		
		$waist_data["data"] = $this->GYMFunction->generate_chart("Waist",$id);
		$waist_data["option"] = $this->GYMFunction->report_option("Waist");
		$this->set("waist_data",$waist_data);
		
		$arms_data["data"] = $this->GYMFunction->generate_chart("Arms",$id);
		$arms_data["option"] = $this->GYMFunction->report_option("Arms");
		$this->set("arms_data",$arms_data);
		
		$fat_data["data"] = $this->GYMFunction->generate_chart("Fat",$id);
		$fat_data["option"] = $this->GYMFunction->report_option("Fat");
		$this->set("fat_data",$fat_data);

		$photos = $this->GymMember->GymMeasurement->find()->where(["user_id"=>$id])->select(["image"])->hydrate(false)->toArray();
		$this->set("photos",$photos);
		
		$history = $this->GymMember->MembershipPayment->find()->contain(["Membership"])->where(["MembershipPayment.member_id"=>$id])->hydrate(false)->toArray();
		
		$this->set("history",$history);
		
		##########################################
		
		$data = $this->GymMember->find()->where(["GymMember.id"=>$id])->contain(['Membership','GymInterestArea'])->select(["Membership.membership_label","GymInterestArea.interest"])->select($this->GymMember)->hydrate(false)->toArray();
		
		$this->set("data",$data[0]);		
	}
	
	public function viewAttendance()
	{	
		$this->set("view",false);	
		if($this->request->is("post"))
		{ 			
			$uid = $this->request->params["pass"][0];			

			//$s_date = date("Y-m-d",strtotime($this->request->data["sdate"]));
			$s_date = $this->GYMFunction->get_db_format_date($this->request->data["sdate"]);
			$e_date = $this->GYMFunction->get_db_format_date($this->request->data["edate"]);			
			
			$conditions = array(
						'conditions' => array(
						'and' => array(
										array('attendance_date <=' => $e_date,
											  'attendance_date >=' => $s_date
											 ),							
							'user_id' => $uid
							)));
			$data = $this->GymMember->GymAttendance->find('all', $conditions)->hydrate(false)->toArray();

			$this->set("data",$data);
			$this->set("s_date",$s_date);
			$this->set("e_date",$e_date);
			$this->set("view",true);
			
		}
	}
	
	public function activateMember($aid)
	{
		$this->autoRender = false;
		$row = $this->GymMember->get($aid);
		$member_email = $row->email;
		$member_id = $row->member_id;
		$membership_status = $row->membership_status;
		$membership_valid_from = date($this->GYMFunction->getSettings("date_format"),strtotime($row->membership_valid_from));
		$membership_valid_to = date($this->GYMFunction->getSettings("date_format"),strtotime($row->membership_valid_to));
		$membership = $this->GYMFunction->get_membership_name($row->selected_membership);
		
		$sys_name = $this->GYMFunction->getSettings('name');
		$sys_email = $this->GYMFunction->getSettings('email');
		
		$message = "Member ID: $member_id\nMembership: $membership\nMembership Status: $membership_status\nMembership Valid From: $membership_valid_from\nMembership Valid To: $membership_valid_to\n\nBest Regards\n$sys_name";
		
		$headers="";
		$headers .= "From: $sys_name<$sys_email> \r\n";
		$headers .= "Reply-To: $sys_email \r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
		$row->activated = 1;
		if($this->GymMember->save($row))
		{		
			@mail($member_email, 'Member Activated', $message, $headers);
			
			$this->Flash->success(__("Success! Member activated successfully."));
			return $this->redirect(["action"=>"memberList"]);
		}
	}
	
	/* public function membershipDropped($id)
	{
		$this->autoRender = false;
		$row = $this->GymMember->get($id)->toArray();;
		$this->request->data['member_id'] = $row['member_id'];
		$this->request->data['membership_status'] = "Dropped";
		//$update = $this->GymMember->patchEntity($row,$this->request->data);
		echo "<script>alert('$id')</script>";
		
	} */
	
	public function export(){
		$this->autoRender = false;
		
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename=user.csv');  
		$output = fopen("php://output", "w");  

		
		fputcsv($output, array('FIRST NAME','MIDDLE NAME','LAST NAME','EMAIL','GENDER','BIRTHDATE','ADDRESS','CITY','STATE','ZIPCODE','MOBILE NO.','USERNAME','MEMBERSHIP','MEMBERSHIP START DATE','MEMBERSHIP END DATE'));  
		
		fputcsv($output, array('Alex','D','Deo','alex@gmail.com','male','1997-05-20','19 Sale-Heyfield Road','sorrento','Victoria','3943','0396630920','alex','Silver Membership','2020-05-20','2021-05-20'));
		/*$mem_tbl = $this->GymMember->find()->select(['member_id','first_name','middle_name','last_name','email','gender','birth_date','address','city','state','zipcode','mobile','username','selected_membership','membership_valid_from','membership_valid_to'])->where(['role_name'=>'member'])->hydrate(false)->toArray(); */
		
		/*foreach($mem_tbl as $data)  
		{  
			$data['selected_membership'] = $this->GYMFunction->get_membership_name($data['selected_membership']);
			fputcsv($output, $data);  
		}  */

		fclose($output); 
		
	}

	public function import(){
		$this->autoRender = false;

		if($this->request->data['import_export'] == 'export'){
			$this->autoRender = false;
		
			header('Content-Type: text/csv; charset=utf-8');  
			header('Content-Disposition: attachment; filename=user.csv');  
			$output = fopen("php://output", "w");  

			
			fputcsv($output, array('MEMBER ID', 'FIRST NAME','MIDDLE NAME','LAST NAME','EMAIL','GENDER','BIRTHDATE','ADDRESS','CITY','STATE','ZIPCODE','MOBILE NO.','USERNAME','MEMBERSHIP','ASSIGNED CLASS','MEMBERSHIP START DATE','MEMBERSHIP END DATE'));  
			
			$mem_tbl = $this->GymMember->find()->select(['member_id','first_name','middle_name','last_name','email','gender','birth_date','address','city','state','zipcode','mobile','username','selected_membership','assign_class','membership_valid_from','membership_valid_to'])->where(['role_name'=>'member'])->hydrate(false)->toArray(); 
			
			foreach($mem_tbl as $data)  
			{  

				$data['selected_membership'] = $this->GYMFunction->get_membership_name($data['selected_membership']);
				fputcsv($output, $data);  
			}  

			fclose($output); 
		}
		else{
			$this->autoRender = false;
			$filename=$this->request->data['import']['tmp_name'];    
			$img_name = $this->request->data['import']["name"];	

			$ext = substr(strtolower(strrchr($img_name, '.')), 1); 
	
			if($ext == 'csv')
			{
				$count = 0;
				if($this->request->data['import']['size'] > 0)
				{
					$file = fopen($filename, "r");
					
					while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
					{
						
						if($count == 0){
							$count++;
							continue;
						}
						$lastid = $this->GymMember->find("all",["fields"=>"id"])->last();
						$lastid = ($lastid != null) ? $lastid->id + 1 : 01 ;
						$m = date("d");
						$y = date("y");
						$prefix = "M".$lastid;
						$member_id = $prefix.$m.$y;

						$member_ship =array('Platinum Membership'=>"1",'Gold Membership'=>"2",'Silver Membership'=>"3");
						$row['activated'] = 1;
						$row['role_name'] = 'member';
						$row['member_id'] = $member_id;
						$row['first_name'] = $getData[0];
						$row['middle_name'] = $getData[1];
						$row['last_name'] = $getData[2];
						$row['member_type'] = 'Member';
						$row['gender'] = $getData[4];
						$row['birth_date'] = date('Y-m-d',strtotime($getData[5]));
						//$row['assign_class'] = 1;
						$row['address'] =  $getData[6];
						$row['city'] =  $getData[7];
						$row['state'] = $getData[8];
						$row['zipcode'] =  $getData[9];
						$row['mobile'] =  $getData[10];
						$row['email'] =  $getData[3];
						$row['username'] =  $getData[11];
						$row['password'] =  '';
						$row['image'] =  'Thumbnail-img.png';
						$row['assign_staff_mem'] =  2;
						$row['selected_membership'] = $member_ship[$getData[12]];
						$row['membership_status'] = ($getData[13] < date('Y-m-d'))?'Continue':'Prospect' ;
						$row['membership_valid_from'] = date('Y-m-d',strtotime($getData[13])) ;
						$row['membership_valid_to'] = date('Y-m-d',strtotime($getData[14]));

						$conn = ConnectionManager::get('default');
						$table_name = TableRegistry::get("gym_member");

						$sql = "INSERT INTO gym_member	(activated,role_name,member_id,first_name,middle_name,last_name,member_type,gender,birth_date,address,city,state,zipcode,mobile,email,username,password,image,assign_staff_mem,selected_membership,membership_status,membership_valid_from,membership_valid_to,created_date) VALUES('".$row['activated']."','".$row['role_name']."','".$row['member_id']."','".$row['first_name']."','".$row['middle_name']."','".$row['last_name']."','".$row['member_type']."','".$row['gender']."','".$row['birth_date']."','".$row['address']."','".$row['city']."','".$row['state']."','".$row['zipcode']."','".$row['mobile']."','".$row['email']."','".$row['username']."','".$row['password']."','".$row['image']."','".$row['assign_staff_mem']."','".$row['selected_membership']."','".$row['membership_status']."','".$row['membership_valid_from']."','".$row['membership_valid_to']."',date('Y-m-d'))";
						
						if($conn->execute($sql))
						{
							$count++;
							/*$member_id = $conn->id;

							/*$membership_amount = $this->GYMFunction->get_membership_amount($row["selected_membership"]);
							$sql1 = "INSERT INTO membership_payment(member_id,membership_id,membership_amount,paid_amount,start_date,end_date,membership_status,payment_status,created_date,created_by) VALUES('".$member_id."','".$row['selected_membership']."','".$membership_amount."',0,'".$row['membership_valid_from']."','".$row['membership_valid_to']."','".$row['membership_status']."',0,date('Y-m-d'),1)";

							$conn->query($sql1);*/
						}
						else {
							$this->Flash->error(__("File Not Import , Please Retry."));
							return $this->redirect(["action"=>"memberList"]);
						}
					}
					if($count > 0)
					{
						$count = $count - 1;
						$this->Flash->success(__("File Import Successfully and $count Record Generated."));
						return $this->redirect(["action"=>"memberList"]);
					}
					fclose($file);  
				}else{
					echo '<script>alert("File is empty")</script>';
					$this->Flash->error(__("File is empty"));
					return $this->redirect(["action"=>"memberList"]);
				}
			}else{
				$this->Flash->error(__("File extension mismatch. please upload csv file"));
				return $this->redirect(["action"=>"memberList"]);
			}
		}
	}
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["viewMember","memberList","viewAttendance"];
		$staff_acc_actions = ["memberList","viewMember","viewAttendance"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			CASE "staff_member":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		
		return parent::isAuthorized($user);
	}

}