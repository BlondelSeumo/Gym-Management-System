<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry; 

class GymNutritionController extends AppController
{
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function nutritionList()
	{
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "staff_member")
		{
			if($this->GYMFunction->getSettings("staff_can_view_own_member"))
			{
				$data = $this->GymNutrition->find("all")->contain(["GymMember"])->where(["GymMember.assign_staff_mem"=>$session["id"]])->select($this->GymNutrition)->group("user_id");
				$data = $data->select(["GymMember.first_name","GymMember.last_name","GymMember.image","GymMember.member_id","GymMember.intrested_area"])->hydrate(false)->toArray();
			}else{
				$data = $this->GymNutrition->find("all")->contain(["GymMember"])->select($this->GymNutrition)->group("user_id");
				$data = $data->select(["GymMember.first_name","GymMember.last_name","GymMember.image","GymMember.member_id","GymMember.intrested_area"])->hydrate(false)->toArray();
			}
		}
		else{			
			$data = $this->GymNutrition->find("all")->contain(["GymMember"])->select($this->GymNutrition)->group(["user_id"]);
			$data = $data->select(["GymMember.first_name","GymMember.last_name","GymMember.image","GymMember.member_id","GymMember.intrested_area"])->hydrate(false)->toArray();
		}	
		$this->set("data",$data);
	}
	
	public function addNutritionSchedule()
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Nutrition Schedule"));
		
		if($session["role_name"] == "staff_member")
		{
			if($this->GYMFunction->getSettings("staff_can_view_own_member"))
			{
				$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","assign_staff_mem"=>$session["id"]]);
				$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
			}
			else{
				$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
				$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
			}
		}
		else{
			$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
			$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
		}
		
		$this->set("members",$members);
		
		if($this->request->is("post"))
		{			
			
			$row = $this->GymNutrition->newEntity();
			$data = $this->request->data;
			$data['start_date'] = $this->GYMFunction->get_db_format_date($this->request->data['start_date']);
			$data['expire_date'] = $this->GYMFunction->get_db_format_date($this->request->data['expire_date']);
			$data["created_by"] = $session["id"];
			$data["created_date"] = date("Y-m-d");
			$row = $this->GymNutrition->patchEntity($row,$data);
			if($this->GymNutrition->save($row))
			{
				$nid = $row->id;
				$save = true;			
			}
			if($save)
			{				
				if($this->nutrition_detail($nid,$data['activity_list']))
				{
					$this->Flash->success(__("Success! Nutrition Added Sucessfully."));	
					return $this->redirect(["action"=>"nutritionList"]);
				}
				else{
					$this->Flash->error(__("Error! Nutrition data couldn't saved.Please try again."));				
				}				
			}
			else{
					$this->Flash->error(__("Error! Nutrition Schedule couldn't saved.Please try again."));				
				}
			
		}
	}
	
	public function nutrition_detail($nutrition_id,$activity_list)
	{
		foreach($activity_list as $val)
			{
				$data_value = json_decode($val);
				$phpobj[] = json_decode(stripslashes($val),true);				
			}
			
			$final_array = array();
			$resultarray =array();
			
			foreach($phpobj as $index => $value)
			{
				$day = array();
				$activity = array();
				foreach($value as $key => $val)
				{
					
					if($key == "days")
					{	foreach($val as $val1)
						{
							$day['day'][] =$val1['day_name'] ;
						}	
					}
					if($key == "activity")
					{
						foreach($val as $val2)
						{
							$activity['activity'][] =array('activity'=>$val2['activity']['activity'],
														'value'=>$val2['activity']['value']						
							) ;
						}
					}
				}
				$resultarray[] = array_merge($day, $activity);
			}
			
		$work_outdata = $resultarray;		
		if(!empty($work_outdata))
		{
			$workout_data = array();
			foreach($work_outdata as  $value)
			{
				foreach($value['day'] as $day)
				{
					foreach($value['activity']  as $actname)
					{
						$workout_data['day_name'] = $day;
						$workout_data['nutrition_time'] = $actname['activity'];
						$workout_data['nutrition_value'] = $actname['value'];
					
						$workout_data['nutrition_id'] = $nutrition_id;
						$workout_data['created_date'] = date("Y-m-d");
						$workout_data['create_by'] = 1;						
						$rws[] = $workout_data;							
					}
				}				
			}			
		}		
	
		$ma_row = $this->GymNutrition->GymNutritionData->newEntities($rws);
		foreach($ma_row as $m_row)
		{
			if($this->GymNutrition->GymNutritionData->save($m_row))
			{
				$success = 1;
			}else{
				$success = 0;
			}
		}
	
		return $success;
	}
	
	public function viewNutirion($id)
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",true);
		$this->set("title",__("View Nutrition Schedule"));
		
		$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
		$this->set("members",$members);
		
		$data = $this->GymNutrition->find()->where(["GymNutrition.user_id"=>$id])->select(["GymNutrition.start_date","GymNutrition.expire_date"]);
		$data = $data->leftjoin(["GymNutritionData"=>"gym_nutrition_data"],
								["GymNutritionData.nutrition_id = GymNutrition.id"]
								)->select($this->GymNutrition->GymNutritionData)->order(['GymNutritionData.nutrition_time'])->hydrate(false)->toArray();
		$wid = 0;
		$nutrition_data = array();
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "GymNutritionData"){
					$wid = $v["nutrition_id"];					
					if($wid != "")
					{
						$nutrition_data[$wid]["start_date"]= $value["start_date"];					
						$nutrition_data[$wid]["expire_date"]= $value["expire_date"];						
						$nutrition_data[$wid][]=$v;							
					}
				}				
			}			
		}		
		$this->set("nutrition_data",$nutrition_data);
		
		if($this->request->is("post"))
		{
			$row = $this->GymNutrition->newEntity();
			$data = $this->request->data;
			$data['start_date'] = $this->GYMFunction->get_db_format_date($this->request->data['start_date']);
			$data['expire_date'] = $this->GYMFunction->get_db_format_date($this->request->data['expire_date']);
			$data["created_by"] = $session["id"];
			$data["created_date"] = date("Y-m-d");
			$row = $this->GymNutrition->patchEntity($row,$data);
			if($this->GymNutrition->save($row))
			{
				$nid = $row->id;
				$save = true;			
			}
			if($save)
			{				
				if($this->nutrition_detail($nid,$data['activity_list']))
				{
					$this->Flash->success(__("Success! Nutrition Added Sucessfully."));	
					return $this->redirect(["action"=>"nutritionList"]);
				}
				else{
					$this->Flash->error(__("Error! Nutrition data couldn't saved.Please try again."));				
				}				
			}
			else{
					$this->Flash->error(__("Error! Nutrition Schedule couldn't saved.Please try again."));				
				}
			
		}
		$this->render("addnutritionSchedule");		
	}
	
	public function memberNutrition()
	{
		$session = $this->request->session()->read("User");
		$id = $session["id"];
		$data = $this->GymNutrition->find()->where(["GymNutrition.user_id"=>$id])->select(["GymNutrition.start_date","GymNutrition.expire_date"]);
		$data = $data->leftjoin(["GymNutritionData"=>"gym_nutrition_data"],
								["GymNutritionData.nutrition_id = GymNutrition.id"]
								)->select($this->GymNutrition->GymNutritionData)->hydrate(false)->toArray();
		
		$nutrition_data = array();
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "GymNutritionData"){
					$wid = $v["nutrition_id"];					
					if($wid != "")
					{
						$nutrition_data[$wid]["start_date"]= $value["start_date"];					
						$nutrition_data[$wid]["expire_date"]= $value["expire_date"];						
						$nutrition_data[$wid][]=$v;							
					}
				}				
			}			
		}		
		$this->set("nutrition_data",$nutrition_data);
	}
	
	public function printNutrition()
	{
		$session = $this->request->session()->read("User");
		$id = $session["id"];
		$data = $this->GymNutrition->find()->where(["GymNutrition.user_id"=>$id])->select(["GymNutrition.start_date","GymNutrition.expire_date"]);
		$data = $data->leftjoin(["GymNutritionData"=>"gym_nutrition_data"],
								["GymNutritionData.nutrition_id = GymNutrition.id"]
								)->select($this->GymNutrition->GymNutritionData)->hydrate(false)->toArray();
		
		$nutrition_data = array();
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "GymNutritionData"){
					$wid = $v["nutrition_id"];					
					if($wid != "")
					{
						$nutrition_data[$wid]["start_date"]= $value["start_date"];					
						$nutrition_data[$wid]["expire_date"]= $value["expire_date"];						
						$nutrition_data[$wid][]=$v;							
					}
				}				
			}			
		}		
		$this->set("nutrition_data",$nutrition_data);
	}

	/* change new */
	public function DeleteNutirion($nid)
	{
		$this->autoRender = false;
		$nid = intval($nid);
		$nutrition_ids = $this->GymNutrition->find("all")->where(["id"=>$nid])->select(["id"])->hydrate(false)->toArray();
		
		$delete =  $this->GymNutrition->query();
		$delete = $delete->delete()->where(["id"=>$nid])->execute();	
		
		if($delete)
		{
			foreach($nutrition_ids as $nut_id)
			{
				$query = $this->GymNutrition->GymNutritionData->query();
				$query->delete()->where(['nutrition_id' => $nut_id["id"]])->execute();
			}
			$this->Flash->success(__("Success! Record Deleted Successfully."));
			return $this->redirect(["action"=>"nutritionList"]);
		}	
	}
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["memberNutrition","printNutrition"];
		// $staff_actions = ["nutritionList","addnutritionSchedule","nutrition_detail","viewNutirion"];
		$acc_actions = ["nutritionList"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return $this->redirect(["action"=>"memberNutrition"]);}
				
			break;
			
			// CASE "staff_member":
				// if(in_array($curr_action,$staff_actions))
				// {return true;}else{ return false;}
			// break;
			
			CASE "accountant":
				if(in_array($curr_action,$acc_actions))
				{return true;}else{return false;}
			break;
		}
		return parent::isAuthorized($user);
	}
}