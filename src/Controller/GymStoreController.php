<?php
namespace App\Controller;
use App\Controller\AppController;

class GymStoreController extends AppController
{
	public function sellRecord()
	{
		
		$session = $this->request->session()->read("User");
		$role = $session["role_name"];
		$user_id = $session['id'];
		$this->set("role",$role);
		
		if($role == 'member'){
			$data = $this->GymStore->find("all")->contain(['GymProduct','GymMember'])->select($this->GymStore)->select(["GymProduct.product_name","GymMember.first_name","GymMember.last_name"])->where(['GymStore.member_id'=>$user_id])->hydrate(false)->toArray();
		}else{
			$data = $this->GymStore->find("all")->contain(['GymProduct','GymMember'])->select($this->GymStore)->select(["GymProduct.product_name","GymMember.first_name","GymMember.last_name"])->hydrate(false)->toArray();
		}
		
		$this->set("data",$data);		
	}
	public function sellProduct()
	{
		$this->set("edit",false);
		
		$members = $this->GymStore->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id",'name' => $members->func()->concat(['first_name'=>'literal', ' ', 'last_name'=>'literal'])])->toArray();
		$this->set("members",$members);
	
		$products = $this->GymStore->GymProduct->find("list",["keyField"=>"id","valueField"=>"product_name"])->toArray();
		$this->set("products",$products);
		
		
		if($this->request->is("post"))
		{
			$row = $this->GymStore->newEntity();
			$product_row = $this->GymStore->GymProduct->get($this->request->data["product_id"]);
			$product_quentity = $product_row->quantity;
			if($this->request->data["quantity"] <= $product_quentity)
			{
				$this->request->data["sell_by"] = 1;
				$this->request->data["sell_date"] = $this->GYMFunction->get_db_format_date($this->request->data['sell_date']);		
				$row = $this->GymStore->patchEntity($row,$this->request->data);
				if($this->GymStore->save($row))
				{
					$product = $this->GymStore->GymProduct->get($this->request->data["product_id"]);
					$product->quantity = ($product->quantity) - ($this->request->data["quantity"]);
					if($this->GymStore->GymProduct->save($product))
					{
						$this->Flash->success(__("Success! Record Saved Successfully."));
						return $this->redirect(["action"=>"sellRecord"]);
					}
				}else{
					$this->Flash->error(__("Error! Record Not Saved.Please Try Again."));
				}
			}else{
				$this->Flash->error(__("Only ".$product_quentity." Item Available in Stock"));
				return $this->redirect(["action"=>"sellProduct"]);
			}
			
		}		
	}
	public function editRecord($pid)
	{	
		
		$this->set("edit",true);		
		$row = $this->GymStore->get($pid);
		$this->set("data",$row->toArray());
		
		$members = $this->GymStore->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id",'name' => $members->func()->concat(['first_name'=>'literal', ' ', 'last_name'=>'literal'])])->toArray();
		$this->set("members",$members);
	
		$products = $this->GymStore->GymProduct->find("list",["keyField"=>"id","valueField"=>"product_name"])->toArray();
		$this->set("products",$products);
		if($this->request->is("post"))
		{
			$old_quentity = $this->request->data["old_quantity"];
			$new_quentity = $this->request->data["quantity"];
			$actual_quentity = $new_quentity - $old_quentity;
			
			$product_row = $this->GymStore->GymProduct->get($this->request->data["product_id"]);
			$available_quentity = $product_row->quantity;
			if($actual_quentity <= $available_quentity)
			{
				$this->request->data["sell_date"] = $this->GYMFunction->get_db_format_date($this->request->data['sell_date']);			
				$row = $this->GymStore->patchEntity($row,$this->request->data);
				if($this->GymStore->save($row))
				{
					$product = $this->GymStore->GymProduct->get($this->request->data["product_id"]);
					$product->quantity = ($product->quantity) - ($actual_quentity);
					if($this->GymStore->GymProduct->save($product))
					{
						$this->Flash->success(__("Success! Record Updated Successfully."));
						return $this->redirect(["action"=>"sellRecord"]);
					}
				}else{
					$this->Flash->error(__("Error! Record Not Updated.Please Try Again."));
				}
			}else{
				$this->Flash->error(__("Only ".$available_quentity." Item Available in Stock"));
				
			}
			
			
			
		}
		$this->render("sellProduct");
	}
	
	public function deleteRecord($did)
	{
		$row = $this->GymStore->get($did);
		if($this->GymStore->delete($row))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully."));
			return $this->redirect(["action"=>"sellRecord"]); 
		} 		
	}
	
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["sellRecord"];
		$staff__acc_actions = ["sellRecord","sellProduct","editRecord","deleteRecord"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			CASE "staff_member":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}
}