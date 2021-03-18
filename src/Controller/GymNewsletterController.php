<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use MCAPI; 

class GymNewsletterController extends AppController {
	public function initialize() {
		parent::initialize();
		require_once(ROOT . DS .'vendor' . DS  . 'mailchimp' . DS . 'MCAPI.class.php');
	}
	public function setting() {
		$key = $this->GymNewsletter->find()->first();
		if($key != null) {
			$key = $key->toArray();
			$key = $key["api_key"];
			$this->set("key",$key);
			$edit = true;
		}else {
			$this->set("key","");
			$edit = false;
		}
		if($this->request->is("post")) {
			if(!$edit) {
				$row = $this->GymNewsletter->newEntity();
				$row = $this->GymNewsletter->patchEntity($row,$this->request->data);
				if($this->GymNewsletter->save($row)) {
					$this->Flash->success(__("Success! Api Key Saved successfully."));
					return $this->redirect($this->referer);
				}
			}else {
				$row = $this->GymNewsletter->find()->first();
				$row->api_key = $this->request->data["api_key"];
				if($this->GymNewsletter->save($row)) {
					$this->Flash->success(__("Success! Api Key Updated successfully."));
				}
			}
		}		
	}
	
	public function syncMail() {
		$key = $this->GymNewsletter->find()->first();
		if($key != null) {
			$key = $key->toArray();
			$key = $key['api_key'];			
		}
		$api = new MCAPI($key);
		$api->useSecure(true);
		$retval = $api->lists();
		
		$this->set("retval",$retval);
		$class_table = TableRegistry::get("ClassSchedule");
		$classes = $class_table->find("list",["keyField"=>"id","valueField"=>"class_name"])->toArray();	
		$this->set("classes",$classes);
		
		if($this->request->is("post")) {
			$mem_tbl = TableRegistry::get("GymMember");
				
			$retval = $api->lists();
			$subcsriber_emil = array();
			$syncmail = $this->request->data['syncmail'];		
			foreach ($syncmail as $id) {				
				$members = $mem_tbl->find("all")->where(["assign_class"=>$id,"role_name"=>"member"])->select(["GymMember.first_name","GymMember.last_name","GymMember.email"])->hydrate(false)->toArray();
				foreach($members as $member) {
					if(trim($member["email"]) !='') {
						$subcsriber_emil[] = array('fname'=>$member["first_name"],'lname'=>$member["last_name"],'email'=>$member["email"]);
					}
				}						
			}	
			if(!empty($subcsriber_emil)) {
				foreach ($subcsriber_emil as $value) {
					$merge_vars = array('FNAME'=>$value['fname'], 'LNAME'=>$value['lname']);
					$subscribe = $api->listSubscribe($this->request->data['list_id'], $value['email'], $merge_vars );						
				}
				$this->Flash->success(__("Success! Mail Synchronize Successfully Done."));
			}
		}
	}
	
	public function Campaign() {
		$key = $this->GymNewsletter->find()->first();
		if($key != null) {
			$key = $key->toArray();
			$key = $key['api_key'];			
		}
		$api = new MCAPI($key);
		$api->useSecure(true);
		$retval = $api->campaigns();
		$retval1 = $api->lists();
		$this->set("retval",$retval);
		$this->set("retval1",$retval1);
		if($this->request->is("post")) {
			$emails = array();
			$listId = $this->request->data['list_id'];
			$campaignId =$this->request->data['camp_id'];
			$listmember = $api->listMembers($listId, 'subscribed', null, 0, 5000 );
			foreach($listmember['data'] as $member) {
				$emails[] = $member['email'];
			}			
			$retval2 = $api->campaignSendTest($campaignId, $emails);
			if ($api->errorCode) {
				$this->Flash->error(__("Campaign Tests Not Sent!"));
			}else {
				$this->Flash->success(__("Campaign Tests Sent!"));
			}
		}		
	}
	
	public function isAuthorized($user) {
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = [];		
		$staff_actions = ["setting","syncMail","Campaign"];
		$acc_actions = [];
		switch($role_name) {			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			CASE "staff_member":
				if(in_array($curr_action,$staff_actions))
				{return true;}else{ return false;}
			break;
			CASE "accountant":
				if(in_array($curr_action,$acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}
}