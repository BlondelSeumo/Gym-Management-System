<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry; 

class GymAttendanceController extends AppController
{
	public function attendance()
    {
		$class_schedule = TableRegistry::get('class_schedule');
		$classes = $class_schedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->hydrate(false)->toArray();
		$this->set("classes",$classes);
		$this->set("view_attendance",false);
		$session = $this->request->session()->read("User");
		
		
		if($this->request->is("post") && isset($this->request->data["attendence"]))
		{
			$class_id = $this->request->data["class_id"];			
			//$att_date = date("Y-m-d",strtotime($this->request->data["curr_date"]));
			$att_date = $this->GYMFunction->get_db_format_date($this->request->data['curr_date']);
			
			if($session["role_name"] == "staff_member")
			{
				$data = $this->GymAttendance->GymMemberClass->find("all")->contain(["GymMember"])->where(["GymMember.member_type"=>"Member","GymMember.membership_status"=>"Continue","GymMemberClass.assign_class" => $class_id,"GymMember.assign_staff_mem"=>$session["id"],"GymMember.membership_valid_from <= " => $att_date,"GymMember.membership_valid_to >= "=> $att_date])->hydrate(false)->toArray();
				
				
			}
			else if($session["role_name"] == "member")
			{
				$data = $this->GymAttendance->find("all")->where(["user_id"=>$session["id"],"class_id"=>$class_id,"attendance_date"=>$att_date])->hydrate(false)->toArray();
			}
			else
			{
				$data = $this->GymAttendance->GymMemberClass->find("all")->contain(["GymMember"])->where(["GymMember.role_name"=>"member","GymMember.member_type"=>"Member","GymMember.membership_status"=>"Continue","GymMemberClass.assign_class" => $class_id,"GymMember.membership_valid_from <= " => $att_date,"GymMember.membership_valid_to >= "=> $att_date])->hydrate(false)->toArray();
				
			}	
			
			$this->set("data",$data);		
			$this->set("class_id",$class_id);		
			$this->set("attendance_date",$att_date);		
			$this->set("view_attendance",true);	
				//var_dump( $att_date);die;
		}
		
		if($this->request->is("post") && isset($this->request->data["save_attendance"]) && isset($this->request->data["attendance"]))
		{
			
			$attendances = $this->request->data["attendance"];			
			$save_row = array();
			//$att_date = date("Y-m-d",strtotime($this->request->data["attendance_date"]));
			$att_date = $this->GYMFunction->get_db_format_date($this->request->data['attendance_date']);
			$class_id = $this->request->data["class_id"];
			foreach($attendances as $att)
			{
				$data = array();
				
				$data["user_id"] = $att; 
				$data["class_id"] = $this->request->data["class_id"]; 
				//$data["attendance_date"] = date("Y-m-d",strtotime($this->request->data["attendance_date"])); 
				$data["attendance_date"] = $this->GYMFunction->get_db_format_date($this->request->data['attendance_date']); 
				$data["status"] = $this->request->data["status"];
				$data["attendance_by"] = 1; 
				$data["role_name"] = "member"; 
				$query = $this->GymAttendance->find("all")->where(["user_id"=>$att,"class_id"=>$class_id,"attendance_date"=>$att_date]);
				$count = $query->count();				
				if($count == 1)
				{
					$erow = $this->GymAttendance->find("all")->where(["user_id"=>$att,"class_id"=>$class_id,"attendance_date"=>$att_date])->first();
					$erow->status = $this->request->data["status"];				
					if($this->GymAttendance->save($erow))
					{
						$success = 1;
					}
				}
				else
				{
					$save_row[] = $data;
				}	
			}
			$ma_row = $this->GymAttendance->newEntities($save_row);
			foreach($ma_row as $m_row)
			{
				if($this->GymAttendance->save($m_row))
				{
					$success = 1;
				}else{
					$success = 0;
				}
			}
			if($success)
			{
				$this->Flash->success(__("Success! Attendance Saved Successfully."));
			}			
		}
    }
	
	public function staffAttendance()
    {		
		$this->set("view_attendance",false);
		
		if($this->request->is("post") && isset($this->request->data["staff_attendence"]))
		{
			//$att_date = date("Y-m-d",strtotime($this->request->data["curr_date"]));
			$att_date = $this->GYMFunction->get_db_format_date($this->request->data['curr_date']);
			$data = $this->GymAttendance->GymMember->find("all")->where(["role_name"=>"staff_member"])->hydrate(false)->toArray();

			$this->set("data",$data);
			$this->set("attendance_date",$att_date);		
			$this->set("view_attendance",true);			
		}
		
		if($this->request->is("post") && isset($this->request->data["save_staff_attendance"]))
		{
			$attendances = array();
			if(isset($this->request->data["attendance"]))
			{
				$attendances = $this->request->data["attendance"];
			}
			
			$save_row = array();
			$att_date = $this->request->data["attendance_date"];
			if(!empty($attendances))
			{
				foreach($attendances as $att)
				{
					$data = array();
					$data["user_id"] = $att; 				
					$data["attendance_date"] = $this->request->data["attendance_date"]; 
					$data["status"] = $this->request->data["status"];
					$data["attendance_by"] = 1; 
					$data["role_name"] = "staff_member"; 
					$query = $this->GymAttendance->find("all")->where(["user_id"=>$att,"attendance_date"=>$att_date]);
					$count = $query->count();				
					if($count == 1)
					{
						$erow = $this->GymAttendance->find("all")->where(["user_id"=>$att,"attendance_date"=>$att_date])->first();
						$erow->status = $this->request->data["status"];				
						if($this->GymAttendance->save($erow))
						{
							$success = 1;
						}
					}
					else
					{
						$save_row[] = $data;
					}
					
					
				}
				$ma_row = $this->GymAttendance->newEntities($save_row);
				foreach($ma_row as $m_row)
				{
					if($this->GymAttendance->save($m_row))
					{
						$success = 1;
					}else{
						$success = 0;
					}
				}
				if($success)
				{
					$this->Flash->success(__("Success! Attendance Saved Successfully."));
				}
			}
			else{
				$this->Flash->error(__("Error! Please Select Member/Staff-Member."));
			}
		}
    }
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["attendance"];
		$staff_acc_actions = ["attendance"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			/*CASE "staff_member":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{ return false;}
			break;*/
			
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		
		return parent::isAuthorized($user);
	}
}
