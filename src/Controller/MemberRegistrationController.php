<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

Class MemberRegistrationController  extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
		$this->loadComponent("GYMFunction");		
	}
	
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);       
        $this->Auth->allow(['index','getMembershipEndDate','addPaymentHistory','getMembershipClasses']);
		if (in_array($this->request->action, ['getMembershipEndDate','getMembershipClasses','get_membership_classes','getMembershipClasses'])) {
			$this->eventManager()->off($this->Csrf);
		}
		 
    }
	
	public function index()
	{		
		$this->viewBuilder()->layout('login');
		
		$member = $this->MemberRegistration->GymMember->newEntity();
		$m = date("d");
		$y = date("y");
		$prefix = "M".$lastid;
		$member_id = $prefix.$m.$y;
		
		$this->set("member_id",$member_id);
		$classes = $this->MemberRegistration->GymMember->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
		$groups = $this->MemberRegistration->GymMember->GymGroup->find("list",["keyField"=>"id","valueField"=>"name"]);
		$interest = $this->MemberRegistration->GymMember->GymInterestArea->find("list",["keyField"=>"id","valueField"=>"interest"]);
		$source = $this->MemberRegistration->GymMember->GymSource->find("list",["keyField"=>"id","valueField"=>"source_name"]);
		$membership = $this->MemberRegistration->GymMember->Membership->find("list",["keyField"=>"id","valueField"=>"membership_label"]);
	
		
		$this->set("classes",$classes);
		$this->set("groups",$groups);
		$this->set("interest",$interest);
		$this->set("source",$source);
		$this->set("membership",$membership);		
		$this->set("edit",false);		
		if($this->request->is("post"))
		{
			$this->request->data['member_id'] = $member_id;
			$image = $this->GYMFunction->uploadImage($this->request->data['image']);
			$this->request->data['image'] = (!empty($image)) ? $image : "Thumbnail-img.png";
			$this->request->data['birth_date'] = $this->GYMFunction->get_db_format_date($this->request->data['birth_date']);
			$this->request->data['membership_valid_from'] = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_from']);
			$this->request->data['membership_valid_to'] = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_to']);
			$this->request->data['created_date'] = date("Y-m-d");
			$this->request->data['assign_group'] = json_encode($this->request->data['assign_group']);			
			$this->request->data['membership_status'] = "Prospect";							
			$this->request->data['member_type'] = "Member";							
			$this->request->data["role_name"]="member";
			
			$member = $this->MemberRegistration->GymMember->patchEntity($member,$this->request->data);
			
			if($this->MemberRegistration->GymMember->save($member))
			{
				$this->request->data['member_id'] = $member->id;
				$this->GYMFunction->add_membership_history($this->request->data);
				if($this->addPaymentHistory($this->request->data))
				{
					// $this->Flash->success(__("Success! Record Saved Successfully."));					
				}
				
				if(!empty($this->request->data["assign_class"]))
				{
					foreach($this->request->data["assign_class"] as $class)
					{
						$new_row = $this->MemberRegistration->GymMemberClass->newEntity();
						$data = array();
						$data["member_id"] = $member->id;
						$data["assign_class"] = $class;
						$new_row = $this->MemberRegistration->GymMemberClass->patchEntity($new_row,$data);
						$this->MemberRegistration->GymMemberClass->save($new_row);
					}
				}
				
				$sys_email = $this->GYMFunction->getSettings("email");
				$sys_name = $this->GYMFunction->getSettings("name");
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
				
				$message = "Hi {$this->request->data["first_name"]},\n\nThank you for registering on our system.\nYour Username: {$this->request->data['username']}\nYou can login once after admin review your account and activates it.\n\nThank You.";
				
				/* $email = new Email('default');
				$email->from(array($sys_email => $sys_name))
					->to($this->request->data["email"])
					->subject("New Registration :{$sys_name}")
					->send($message); */
				@mail($this->request->data["email"],_("New Registration : {$sys_name}"),$message,$headers);
				
				//$this->Flash->success(__("Registration completed successfully. Please Check email"));
				$this->Flash->success(__("Registration completed successfully. You will get email after activation"));
				// echo "<script>alert('Success! Registration completed successfully.');</script>";
				//Send Mail
				
				return $this->redirect(["controller"=>"users","action"=>"login"]);
				
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
		}
	}
	
	private function addPaymentHistory($data)
	{
		$row = $this->MemberRegistration->MembershipPayment->newEntity();
		$save["member_id"] = $data["member_id"];
		$save["membership_id"] = $data["selected_membership"];
		$save["membership_amount"] = $this->GYMFunction->get_membership_amount($data["selected_membership"]);
		$save["paid_amount"] = 0;
		$save["start_date"] = $data["membership_valid_from"];
		$save["end_date"] = $data["membership_valid_to"];
		$save["payment_status"] = 0;
		$save["created_date"] = date("Y-m-d");
		/* $save["created_dby"] = 1; */
		$row = $this->MemberRegistration->MembershipPayment->patchEntity($row,$save);
		if($this->MemberRegistration->MembershipPayment->save($row))
		{return true;}else{return false;}
	}
	
	
	public function regComplete()
	{
		$this->autoRender = false;
		echo "<br><p><i><strong>Success!</strong> Registration completed successfully.</i></p>";
		echo "<p><i><a href='{$this->request->base}/Users'>Click Here</a> to Redirect on login page.</i></p>";
	}
	
	public function getMembershipEndDate()
	{
		$this->autoRender=false;
		
		if($this->request->is("ajax"))
		{

			$date = $this->request->data["date"];
			$date = str_replace("/","-",$date);
			$membership_id = $this->request->data["membership"];
			$date1 = date("Y-m-d",strtotime($date));
			$membership_table =  TableRegistry::get("Membership");
			$row = $membership_table->get($membership_id)->toArray();
			$period = $row["membership_length"];
			$end_date = date("Y-m-d",strtotime($date1 . " + {$period} days"));
			echo $end_date;
			die;
		}
	}
	public function getMembershipClasses()
	{
		if($this->request->is("ajax"))
		{
			$membership_id = $this->request->data["m_id"];
			$mem_tbl = TableRegistry::get("Membership");
			$class_tbl = TableRegistry::get("ClassSchedule");
			$mem_classes = $mem_tbl->get($membership_id)->toArray();
			$mem_classes = json_decode($mem_classes["membership_class"]);
			$data = null;
			if(!empty($mem_classes))
			{				
				foreach($mem_classes as $class)
				{
					$class_data = $class_tbl->find()->where(["id"=>$class])->hydrate(false)->toArray();
					if(!empty($class_data))
					{
						$class_data = $class_data[0];
						$data .= "<option value='{$class_data['id']}'>{$class_data['class_name']}</option>";
					}					
				}
			}
			echo $data;			
		}
		
		die;
	}
}