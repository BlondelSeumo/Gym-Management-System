<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

Class AdminProfileController extends AppController
{
	public function initialize()
	{
			parent::initialize();
			$this->loadComponent("GYMFunction");
	}
		
	public function editProfile($eid)
	{
		$this->set("edit",true);
		$session = $this->request->session()->read("User");
		$session_w = $this->request->session();
		if($session["id"] != $eid)
		{
			return $this->redirect(["controller"=>"Dashboard"]);
			die;
		}
		$tbl = TableRegistry::get("GymMember");
		$data = array();
		$row = $tbl->get($eid);		
		$this->set("data",$row->toArray());
		if($this->request->is("post"))
		{
			$ext = $this->GYMFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$post = $this->request->data;
				$row->first_name = $post["first_name"];
				$row->last_name = $post["last_name"];
				$row->email = $post["email"];
				if(!empty($post["image"]["name"]))
				{
					$row->image = $this->GYMFunction->uploadImage($post['image']);
					// $row->image = (!empty($image)) ? $image : "logo.png";
					$session_w->write("User.profile_img",$row->image);
				}
				
				$row->username = $post["username"];
				
				if(!empty($post["password"]))
				{
					$row->password = $post["password"];
				}
				
				if($tbl->save($row))
				{
					$this->Flash->success(__("Success! Record Saved Successfully.Please login again to see changes."));
					return $this->redirect($this->referer());
				}
				else
				{				
					if($row->errors())
					{	
						foreach($row->errors() as $error)
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
				return $this->redirect(["action"=>"editProfile",$eid]);
			}
		}
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = [];
		$staff_acc_actions = [];
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