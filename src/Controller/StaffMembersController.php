<?php
namespace App\Controller;
use Cake\App\Controller;
use Cake\Network\Session\DatabaseSession;

class StaffMembersController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("GYMFunction");	
	}
	
	public function staffList()
	{		
		$role_name = $this->request->session()->read('Auth.User.role_name');
		$staff_id = $this->request->session()->read('Auth.User.id');
		
		if($role_name != 'staff_member'){
			$data = $this->StaffMembers->GymMember->find()->contain(['GymRoles'])->where(["GymMember.role_name"=>"staff_member"])->select(['GymRoles.name'])->select($this->StaffMembers->GymMember)->hydrate(false)->toArray();
		}else{
			$data = $this->StaffMembers->GymMember->find()->contain(['GymRoles'])->where(["GymMember.role_name"=>"staff_member","GymMember.id"=>$staff_id])->select(['GymRoles.name'])->select($this->StaffMembers->GymMember)->hydrate(false)->toArray();	
		}
		$this->set("data",$data);
	}
	
	public function addStaff()
	{
		$this->set("edit",false);
		$this->set("title",__("Add Staff Member"));
		
		$roles = $this->StaffMembers->GymMember->GymRoles->find("list",["keyField"=>"id","valueField"=>"name"])->hydrate(false)->toArray();
		$this->set("roles",$roles);

		$specialization = $this->StaffMembers->GymMember->Specialization->find("list",["keyField"=>"id","valueField"=>"name"])->hydrate(false)->toArray();
		$this->set("specialization",$specialization);		
		
		if($this->request->is("post"))
		{
			$ext = $this->GYMFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$staff = $this->StaffMembers->GymMember->newEntity();
							
				$image = $this->GYMFunction->uploadImage($this->request->data['image']);
				$this->request->data['image'] = (!empty($image)) ? $image : "Thumbnail-img.png";
				//$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
				 $this->request->data['birth_date'] = $this->GYMFunction->get_db_format_date($this->request->data['birth_date']); 
				$this->request->data['created_date'] = date("Y-m-d");
				$this->request->data['s_specialization'] = json_encode($this->request->data['s_specialization']);
				$this->request->data["role_name"]="staff_member";
				//this code add for api
				$this->request->data['activated']=1;
				//end
				$staff = $this->StaffMembers->GymMember->patchEntity($staff,$this->request->data);
		
				if($this->StaffMembers->GymMember->save($staff))
				{
					
					$this->Flash->success(__("Success! Record Successfully Saved."));
					return $this->redirect(["action"=>"staffList"]);
				}else
				{		
					 
					if($staff->errors())
					{	
						foreach($staff->errors() as $error)
						{
							foreach($staff as $key=>$value)
							{
								$this->Flash->error(__($value));
							}						
						}
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"add-staff"]);
			}
		}
	}
	
	public function editStaff($id)
	{
		$this->set("edit",true);
		$this->set("title",__("Edit Staff Member"));
		
		$data = $this->StaffMembers->GymMember->get($id)->toArray();
		$roles = $this->StaffMembers->GymMember->GymRoles->find("list",["keyField"=>"id","valueField"=>"name"])->hydrate(false)->toArray();
		$specialization = $this->StaffMembers->GymMember->Specialization->find("list",["keyField"=>"id","valueField"=>"name"])->hydrate(false)->toArray();
		
		$this->set("specialization",$specialization);
		$this->set("roles",$roles);		
		$this->set("data",$data);
		$this->render("AddStaff");
		
		if($this->request->is("post"))
		{
			$ext = $this->GYMFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$row = $this->StaffMembers->GymMember->get($id);
				//$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
				$this->request->data['birth_date'] = $this->GYMFunction->get_db_format_date($this->request->data['birth_date']); 
				
				//activated status for api
				$this->request->data['activated'] = 1;
				//end
				
				$this->request->data['s_specialization'] = json_encode($this->request->data['s_specialization']);
				$image = $this->GYMFunction->uploadImage($this->request->data['image']);
				if($image != "")
				{
					$this->request->data['image'] = $image;
				}else{
					unset($this->request->data['image']);
				}
				/* $this->request->data['image'] = (!empty($image)) ? $image : "logo.png";*/
				$update = $this->StaffMembers->GymMember->patchEntity($row,$this->request->data);
				if($this->StaffMembers->GymMember->save($update))
				{
					$this->Flash->success(__("Success! Record Updated Successfully."));
					return $this->redirect(["action"=>"staffList"]);
				}else
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
				return $this->redirect(["action"=>"edit-staff",$id]);
			}
		}
	}
	
	public function deleteStaff($id)
	{
		$row = $this->StaffMembers->GymMember->get($id);
		if($this->StaffMembers->GymMember->delete($row))
		{
			$this->Flash->success(__("Success! Staff Member Deleted Successfully."));
			return $this->redirect($this->referer());
		}
	}
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;	
		$members_actions = ["staffList"];
		$staff_acc_actions = ["staffList"];
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