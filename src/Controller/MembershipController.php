<?php

namespace App\Controller;

use App\Controller\AppController;

use Cake\Routing\RouteBuilder;

use Cake\Routing\Router;

use Cake\ORM\TableRegistry;



Class MembershipController extends AppController

{	

	public function initialize()

    {

        parent::initialize();		

		

		$this->loadComponent('Csrf');

        $this->loadComponent('RequestHandler');		

		$this->loadComponent("GYMFunction");

    }

	

	public function add()

	{			

		$this->set("membership",null);			

		$this->set("edit",false);		

		$this->set("title",__("Add Membership"));		

		$catgories = $this->Membership->Category->find("list",["keyField"=>"id","valueField"=>"name"]);		

		$catgories = $catgories->toArray();		

		

		$classes = $this->Membership->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->toArray();
		
		$this->set("classes",$classes);	

		

		$installment_plan = $this->Membership->Installment_Plan->find("list",["keyField"=>"id","valueField"=>"concatenated"]); //Merging two table column ["col1","col2"]

		$installment_plan->select(['id',

									'concatenated' => $installment_plan->func()->concat([

													'number' => 'literal',

													' ',

													'duration' => 'literal'

													])

								]);  // adding space between two column cakephp way.

								

		$installment_plan = $installment_plan->toArray();		

		

		$this->set('installment_plan',$installment_plan);

		$this->set('categories',$catgories);

		$membership = $this->Membership->newEntity();

		if($this->request->is("post"))

		{

			$ext = $this->GYMFunction->check_valid_extension($this->request->data('gmgt_membershipimage')['name']);

			if($ext != 0)

			{

				$new_name = $this->GYMFunction->uploadImage($this->request->data["gmgt_membershipimage"]);

				$this->request->data["gmgt_membershipimage"] =  $new_name;

				$this->request->data["created_date"] = date("Y-m-d");			

				$this->request->data["membership_class"] = json_encode($this->request->data["membership_class"]);

				

				

				if(!isset($this->request->data["limit_days"]))

				{

					$this->request->data["limit_days"]=null;

					$this->request->data["limitation"]=null;

				}

				$membership = $this->Membership->patchEntity($membership,$this->request->data());

					

				if($this->Membership->save($membership))

				{

					$this->Flash->success(__("Success! Record Saved Successfully"));

					return $this->redirect(["action"=>"membershipList"]);

				}else{

					$this->Flash->error(__("Error! There was an error while saving,Please try again later."));

				}

			}

			else{

				$this->Flash->error(__("Invalid File Extension, Please Retry."));

				return $this->redirect(["action"=>"add"]);

			}

		}

	}

	

	public function membershipList()

	{

		$membership_data = $this->Membership->find("all")->toArray();   

		

		$this->set("membership_data",$membership_data);

	}

	

	public function editMembership($id)

	{	$this->set("edit",true);	

		$this->set("membership",null);

		$this->set("title",__("Edit Membership"));	

		

		$classes = $this->Membership->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->toArray();

		$this->set("classes",$classes);	

		

		$membership_data = $this->Membership->get($id)->toArray();

		

		$catgories = $this->Membership->Category->find("list",["keyField"=>"id","valueField"=>"name"]);		

		$catgories = $catgories->toArray();		

		

		$installment_plan = $this->Membership->Installment_Plan->find("list",["keyField"=>"id","valueField"=>"concatenated"]); //Merging two table column ["col1","col2"]

		$installment_plan->select(['id',

									'concatenated' => $installment_plan->func()->concat([

													'number' => 'literal',

													' ',

													'duration' => 'literal'

													])

								]);  // adding space between two column cakephp way.

								

		$installment_plan = $installment_plan->toArray();		

		$membership_class = json_decode($membership_data["membership_class"]);

		//$membership_class = json_decode($membership_data["membership_class"]);

		

		$this->set('installment_plan',$installment_plan);

		$this->set('categories',$catgories);

		$this->set("membership_data",$membership_data);

		$this->set("membership_class",$membership_class);

		

		if($this->request->is("post"))

		{



			//$ext = $this->GYMFunction->check_valid_extension($this->request->data('gmgt_membershipimage')['name']);

			//debug($this->request->data);die;

			$ext = $this->GYMFunction->check_valid_extension($this->request->data['gmgt_membershipimage']['name']);

			//debug($ext);die;

			if($ext != 0)

			{

				$row = $this->Membership->get($id);



				if($this->request->data['gmgt_membershipimage']['name'] != "")

				{

					$new_name = $this->GYMFunction->uploadImage($this->request->data["gmgt_membershipimage"]);

					$this->request->data["gmgt_membershipimage"] =  $new_name;



					//debug(WWW_ROOT."/upload/".$row['gmgt_membershipimage']);



					if($row['gmgt_membershipimage'] != '')

					{

						unlink(WWW_ROOT."/upload/".$row['gmgt_membershipimage']);

					}

				}

				else{

					unset($this->request->data['gmgt_membershipimage']);

				}



				if(!isset($this->request->data["limit_days"]))

				{

					$this->request->data["limit_days"]=null;

					$this->request->data["limitation"]=null;

				}

				$this->request->data["membership_class"] = json_encode($this->request->data["membership_class"]);

				



				$membership = $this->Membership->patchEntity($row,$this->request->data);

				if($this->Membership->save($membership))

				{

					$this->Flash->success(__("Success! Record Updated Successfully"));

					return $this->redirect(["action"=>"membershipList"]);

				}else{

					$this->Flash->error(__("Error! There was an error while updating,Please try again later."));

				}

			}else{

				$this->Flash->error(__("Invalid File Extension, Please Retry."));

				return $this->redirect(["action"=>"edit-membership",$id]);

			}

		}

		$this->render("add");

	}

	

	public function viewActivity($mid)

	{

		$activities_list = $this->Membership->Activity->find("list",["keyField"=>"id","valueField"=>"title"]);

		$activities_list = $activities_list->toArray();

		

		$selected_activities = $this->Membership->Membership_Activity->find("list",["keyField"=>"id","valueField"=>"activity_id"])->where(["membership_id"=>$mid]);

		$selected_activities = array_values($selected_activities->toArray());

	

		$assigned_activities = $this->Membership->Membership_Activity->find("all")->where(["membership_id"=>$mid])->contain(["Activity"])->select($this->Membership->Membership_Activity);

		$assigned_activities = $assigned_activities->select(["Activity.cat_id","Activity.assigned_to"])->hydrate(false)->toArray();

		

		$this->set("activities",$activities_list);

		$this->set("selected_activities",$selected_activities);

		$this->set("assigned_activities",$assigned_activities);	



		if($this->request->is("post"))

		{

			$membership_activity = TableRegistry::get("Membership_Activity");			

			$data = $this->request->data;

			$delete_row= $membership_activity->deleteAll(["membership_id"=>$data['membership_id']]);

			$save_data = array();

			foreach($data["activity_id"] as $activity)

			{				

				$save_data[] = ["membership_id"=>$data["membership_id"],"activity_id"=>$activity,"created_date"=>date("Y-m-d")];

			}			

			$rows = $membership_activity->newEntities($save_data);

			foreach($rows as $row)

			{

				$membership_activity->save($row);

			}

			$this->Flash->Success(__("Success! Activity Successfully Assigned."));

			return $this->redirect($this->here);

		}		

	}

	

	public function deleteActivity($id)

	{

		$row = $this->Membership->Membership_Activity->get($id);		

		if($this->Membership->Membership_Activity->delete($row))

		{

			$this->Flash->Success(__("Success! Activity Unassigned Successfully."));

			return $this->redirect($this->referer());

		}

	}

	

	public function isAuthorized($user)

	{

		$role_name = $user["role_name"];

		$curr_action = $this->request->action;	

		$members_actions = ["membershipList"];

		$staff_acc_actions = ["membershipList","add","editMembership","viewActivity","deleteActivity"];

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