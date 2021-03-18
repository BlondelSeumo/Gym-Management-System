<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\Core\Configure;

class GymMessageController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("GYMFunction");
		$session = $this->request->session()->read("User");
		$uid = intval($session["id"]); /* Current userid */
		$query = $this->GymMessage->find("all")->where(["receiver"=>$uid,"status"=> 0]);
		$unread_messages = $query->count();
		$this->set("unread_messages",$unread_messages);
	}
	
	public function index()
	{
		
	}
	
	public function composeMessage()
    {
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member" && !$this->GYMFunction->getSettings("enable_message"))
		{
			return $this->redirect(["action"=>"inbox"]);
		}
		
		$classes = $this->GymMessage->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->toArray();
		$class["all"] = __("All");
		$finalClass = array_merge($class,$classes);
		$this->set("classes",$finalClass);
		$date = date("Y-m-d H:i:s");
		
		if($this->request->is("post"))
		{	
			
			$role = $this->request->data["receiver"];	

			if($role == 'member' || $role == 'staff_member' || $role == 'accountant'|| $role == 'administrator')
			{
				$class_id = $this->request->data['class_id'];
				
				//debug($class_id);die;
				//if(($role == 'member' || $role == 'staff_member') && $class_id != 'all' )
				if($role == 'member' && $class_id != 'all')
				{

					$gym_member_class = TableRegistry::get('gym_member_class');
					$data = $this->GymMessage->GymMember->find("all")->where(["GymMember.role_name"=>$role])->select($this->GymMessage->GymMember);
					
					$member_ids = $data->innerjoin(["gym_member_class"=>"gym_member_class"],
					["gym_member_class.member_id = GymMember.id"])->select($gym_member_class)->where(['gym_member_class.assign_class'=>$class_id])->hydrate(false)->toArray();
					
					debug($member_ids);die;
				}
				else
				{

					$member_ids = $this->GymMessage->GymMember->find("all")->where(["role_name"=>$role])->select(["id"])->hydrate(false)->toArray();	
				}
				$records = array();

				if(!empty($member_ids))
				{					
					foreach($member_ids as $key => $value)
					{
						
						$mid = $value["id"];
					
						$data = array();
						$data["sender"] = $session["id"]; /* current userid*/
						$data["receiver"] = $mid;
						$data["date"] = $date;
						$data["subject"] = $this->request->data["subject"];
						$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);
						$data["status"] =  0;
						$records[] = $data;
						
					}
					
					$rows = $this->GymMessage->newEntities($records);
				
					foreach($rows as $row)
					{
						if($this->GymMessage->save($row))
						{$saved = true;} else{$saved = false;}
					}
				}else{
					$saved = false;
				}		
			}
			else
			{		
				$mid = $this->request->data["receiver"];
				$this->request->data["date"] = $date;
				$this->request->data["sender"] = $session["id"]; /* current userid*/
				$this->request->data["status"] = 0;
				$row = $this->GymMessage->newEntity();
				$row = $this->GymMessage->patchEntity($row,$this->request->data);
				if($this->GymMessage->save($row))
				{$saved = true;}else{$saved = false;}
			}
			
			/*if($this->request->data["class_id"] == "all")
			{
				$member_ids = $this->GymMessage->GymMember->find("all")->where(["role_name"=>"member"])->select(["id"])->hydrate(false)->toArray();
				$records = array();
				if(!empty($member_ids))
				{					
					foreach($member_ids as $key => $value)
					{
						$mid = $value["id"];
						$data = array();
						$data["sender"] = $session["id"];
						$data["receiver"] = $mid;
						$data["date"] = $date;
						$data["subject"] = $this->request->data["subject"];
						$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);
						$data["status"] =  0;
						$records[] = $data;
					}
				}
				
				$rows = $this->GymMessage->newEntities($records);
				foreach($rows as $row)
				{
					if($this->GymMessage->save($row))
					{$saved = true;} else{$saved = false;}
				}	
			}*/
			
			if($saved)
			{$this->Flash->success(__("Success! Message Sent Successfully."));}
			else
			{$this->Flash->error(__("Error! Message Couldn't be Sent, Please Try Again."));}			
		}
    }
	
	public function inbox()
    {
		$session = $this->request->session()->read("User");
		$uid = $session["id"]; /* Current userid */
		$messages = $this->GymMessage->find("all")->contain(["GymMember"])->where(["receiver"=>$uid])->select($this->GymMessage)->select(["GymMember.first_name","GymMember.last_name"])->hydrate(false)->toArray(); 
		$this->set("messages",$messages);		
    }
	
	public function sent()
    {
		$session = $this->request->session()->read("User");
		$uid = $session["id"]; /* Current userid */
		$messages = $this->GymMessage->find("all")->where(["GymMessage.sender"=>$uid])->limit(30)->select($this->GymMessage)->order(['GymMessage.id'=>'desc']);
		$messages = $messages->leftjoin(["GymMember"=>"gym_member"],
									  ["GymMember.id = GymMessage.receiver"])->select(["GymMember.first_name","GymMember.last_name"])->hydrate(false)->toArray();
		$this->set("messages",$messages);		
    }	
	
    public function viewMessage($vid)
    {
		$data = $this->GymMessage->find("all")->where(["GymMessage.id"=>intval($vid)])->contain(["GymMember"])->select($this->GymMessage)->select(["GymMember.first_name","GymMember.last_name","GymMember.email"])->hydrate(false)->toArray();
		$this->set("data",$data[0]);	
		$row = $this->GymMessage->get($vid);
		$row->status = 1;
		$this->GymMessage->save($row);
	}  
	
	public function viewSentMessage($vid)
    {
		$data = $this->GymMessage->find("all")->where(["GymMessage.id"=>intval($vid)])->select($this->GymMessage);
		$data = $data->leftjoin(["GymMember"=>"gym_member"],
								["GymMember.id = GymMessage.receiver"])->select(["GymMember.first_name","GymMember.last_name","GymMember.email"])->hydrate(false)->toArray();
		$temp = $data[0]["GymMember"];
		unset($data[0]["GymMember"]);
		$data[0]["gym_member"] = $temp;		
		$this->set("data",$data[0]);
		$this->render("viewMessage");
	}
	
	public function deleteMessage($did)
	{
		$row = $this->GymMessage->get($did);
		if($this->GymMessage->delete($row))
		{
			$this->Flash->success(__("Success! Message Deleted Successfully."));
			return $this->redirect(["action"=>"inbox"]);
		}
	}	
}
?>