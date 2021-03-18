<?php
namespace App\Controller;
use Cake\App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use  Cake\Utility\Xml; 

class GeneralSettingController extends AppController{

	public function initialize()
	{
		parent::initialize();
		$this->loadcomponent("GYMFunction");
	}
	
	public function saveSetting()
	{
		$query = $this->GeneralSetting->find("all");
		$count = $query->count();
		if($count == 1)
		{			
			$results = $this->GeneralSetting->find()->all();
			$row = $results->first();
			$this->set("data",$row);
			$this->set("edit",true);			
		}
		else{
			$this->set("edit",false);
			$this->set("data","");	
		}	
		
		$time_zones = timezone_abbreviations_list();
		$this->set("timezone",$time_zones);
		
		$xml = Xml::build('../vendor/xml/countrylist.xml');			
		$currency_xml = Xml::toArray(Xml::build('../vendor/xml/currency-symbols.xml'));;			
		$this->set('xml',$xml);			
		$this->set('currency_xml',$currency_xml['currency-symbol']['entry']);	

		if($this->request->is("post"))
		{
			$logo_ext = $this->GYMFunction->check_valid_extension($this->request->data['gym_logo']['name']);
			$cover_ext = $this->GYMFunction->check_valid_extension($this->request->data['cover_image']['name']);
			$datepicker_lang = $this->request->data['sys_language'];
			$calendar_lang = $this->request->data['sys_language'];
			$this->request->data['datepicker_lang'] = $datepicker_lang;
			$this->request->data['calendar_lang'] = $calendar_lang;
			
			if($logo_ext != 0 && $cover_ext != 0)
			{
				$session = $this->request->session();
				$session->write("User.name",$this->request->data["name"]);
				$session->write("User.left_header",$this->request->data["left_header"]);
				$session->write("User.is_rtl",$this->request->data["enable_rtl"]);
				$session->write("User.dtp_lang",$this->request->data["datepicker_lang"]);
				$session->write("User.footer",$this->request->data["footer"]);
				if($count == 0)
				{
					$settings = $this->GeneralSetting->newEntity();
					$logo_name =  $this->GYMFunction->uploadImage($this->request->data['gym_logo']);
					$cover_name =  $this->GYMFunction->uploadImage($this->request->data['cover_image']);
					$this->request->data['gym_logo'] = $logo_name;
					$this->request->data['cover_image'] = $cover_name;
					$session->write("User.logo",$this->request->data["gym_logo"]);
					$settings = $this->GeneralSetting->patchEntity($settings,$this->request->data);
					if($this->GeneralSetting->save($settings))
					{
						$this->Flash->success(__("Success! Settings Saved Successfully."));
					}
				}
				else{
					$results = $this->GeneralSetting->find()->all();
					$update_row = $results->first();				
					$logo_name =  $this->GYMFunction->uploadImage($this->request->data['gym_logo']);
					$cover_name =  $this->GYMFunction->uploadImage($this->request->data['cover_image']);
					if($this->request->data['gym_logo']['name'] != "")
					{
						$this->request->data['gym_logo'] = $logo_name;
						$session->write("User.logo","/webroot/upload/".$this->request->data["gym_logo"]);
					}else{
						$this->request->data['gym_logo'] = $this->request->data['old_gym_logo'];
					}
					if($this->request->data['cover_image']['name'] != "")
					{
						$this->request->data['cover_image'] = $cover_name;
					}else{
						$this->request->data['cover_image'] = $this->request->data['old_cover_image'];
					}
					$update = $this->GeneralSetting->patchEntity($update_row,$this->request->data);
					if($this->GeneralSetting->save($update))
					{
						$this->Flash->success(__("Success! Settings Saved Successfully."));
						return $this->redirect($this->here);
					}
					
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"save-setting"]);
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