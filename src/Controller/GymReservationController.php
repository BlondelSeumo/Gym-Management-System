<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
// use App\Controller\AppController;

class GymReservationController extends AppController
{
	public function reservationList()
    {
		$data = $this->GymReservation->find("all")->contain(["GymEventPlace"])->select($this->GymReservation)->select(["GymEventPlace.place"])->hydrate(false)->toArray();
		$this->set("data",$data);
    }
	
	public function addReservation()
    {
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$event_places = $this->GymReservation->GymEventPlace->find("list",["keyField"=>"id","valueField"=>"place"])->hydrate(false);
		$this->set("event_places",$event_places);
		
		if($this->request->is("post"))
		{
			$flag = 0;
			$event_list = $this->GymReservation->find()->hydrate(false)->toArray();

			foreach ($event_list as $key => $value) {
				if(($value['event_date'] == $this->GYMFunction->get_db_format_date($this->request->data['event_date'])) && ($value['start_time'] == $this->request->data['starttime']))
				{
					$flag = 1;
					
				}else{
					$flag = 0;
					
				}
			}

			//echo $flag; die;
			if($flag == 0){
				$row = $this->GymReservation->newEntity();
				$this->request->data["created_by"] = $session["id"];
				$this->request->data["created_date"] = date("Y-m-d");
				$this->request->data["event_date"] = $this->GYMFunction->get_db_format_date($this->request->data['event_date']);		
				$this->request->data['start_time'] = $this->request->data['starttime'];
				$this->request->data['end_time'] = $this->request->data['endtime'];
				$row = $this->GymReservation->patchEntity($row,$this->request->data);		
				if($this->GymReservation->save($row))
				{
					$this->Flash->success(__("Success! Record Saved Successfully"));
					return $this->redirect(["action"=>"reservationList"]);
				}
			}else{
				$this->Flash->error(__("Error! Event already create in selected date and time and same location."));
				return $this->redirect(["action"=>"addReservation"]);
			}
			/*$row = $this->GymReservation->newEntity();
			$this->request->data["created_by"] = $session["id"];
			$this->request->data["created_date"] = date("Y-m-d");
			$this->request->data["event_date"] = $this->GYMFunction->get_db_format_date($this->request->data['event_date']);		
			$this->request->data['start_time'] = $this->request->data['starttime'];
			$this->request->data['end_time'] = $this->request->data['endtime'];
			$row = $this->GymReservation->patchEntity($row,$this->request->data);		
			if($this->GymReservation->save($row))
			{
				$this->Flash->success(__("Success! Record Saved Successfully"));
				return $this->redirect(["action"=>"reservationList"]);
			}*/
		}
    }
	 public function editReservation($id)
    {
		$this->set("edit",true);
		$row = $this->GymReservation->get($id);	
		
		$this->set("data",$row->toArray());
		
		$event_places = $this->GymReservation->GymEventPlace->find("list",["keyField"=>"id","valueField"=>"place"])->hydrate(false);
		$this->set("event_places",$event_places);
		
		$this->render("addReservation");
		$row = "";
		if($this->request->is("post"))
		{
			//$flag = 0;
			$event_list = $this->GymReservation->find()->hydrate(false)->toArray();

			foreach ($event_list as $key => $value) {

				if(($value['event_date'] == $this->GYMFunction->get_db_format_date($this->request->data['event_date'])) && ($value['start_time'] == $this->request->data['starttime']) && ($value['place_id'] == $this->request->data['place_id']))
				{
					$flag = 1;
					break;
				}else{
					$flag = 0;
					
				}
			}

			if($flag == 0){
				$row = $this->GymReservation->get($id);			
				$this->request->data["event_date"] = $this->GYMFunction->get_db_format_date($this->request->data['event_date']);		
				$this->request->data['start_time'] = $this->request->data['starttime'];
				$this->request->data['end_time'] = $this->request->data['endtime'];
				
				$row = $this->GymReservation->patchEntity($row,$this->request->data);
				if($this->GymReservation->save($row))
				{
					$this->Flash->success(__("Success! Record Updated Successfully"));
					return $this->redirect(["action"=>"reservationList"]);
				}
			}else{
				$this->Flash->error(__("Error! Event already create in selected date and time and same location"));
				$row = $this->GymReservation->get($id);	
		
				$this->set("data",$row->toArray());
				$this->render("addReservation");
			}
			/*$row = $this->GymReservation->get($id);			
			$this->request->data["event_date"] = $this->GYMFunction->get_db_format_date($this->request->data['event_date']);		
			$this->request->data['start_time'] = $this->request->data['starttime'];
			$this->request->data['end_time'] = $this->request->data['endtime'];
			
			$row = $this->GymReservation->patchEntity($row,$this->request->data);
			if($this->GymReservation->save($row))
			{
				$this->Flash->success(__("Success! Record Updated Successfully"));
				return $this->redirect(["action"=>"reservationList"]);
			}
			*/
		}
    }
	
	public function deleteReservation($did)
    {
		$drow = $this->GymReservation->get($did);
		if($this->GymReservation->delete($drow))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully"));
			return $this->redirect(["action"=>"reservationList"]);
		}
    }
	
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["reservationList"];
		//$staff__acc_actions = ["addReservation","reservationList"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			/*CASE "staff_member":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{return false;}
			break;*/
		}		
		return parent::isAuthorized($user);
	}
}
