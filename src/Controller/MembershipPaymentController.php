<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Gmgt_paypal_class;

class MembershipPaymentController extends AppController
{	
	public function initialize()
	{
		parent::initialize();
		require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'paypal_class.php');
		$this->loadComponent("GYMFunction");
	}
	
    public function paymentList() {	
		$new_session = $this->request->session();
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member") {			
			$data = $this->MembershipPayment->find("all")->contain(["Membership","GymMember"])->where(["GymMember.id"=>$session["id"]])->hydrate(false)->toArray();
		}else {
			$data = $this->MembershipPayment->find("all")->contain(["Membership","GymMember"])->hydrate(false)->toArray();
		}
		$this->set("data",$data);
		if($this->request->is("post")) {			
			$mp_id = $this->request->data["mp_id"];
			$row = $this->MembershipPayment->get($mp_id);
			if($this->request->data["payment_method"] == "Stripe" && $session["role_name"] == "member") {
				if(!empty($this->request->data['stripeToken'])) {
					$token = $this->request->data['stripeToken'];
					$email = $this->request->data['stripeEmail'];

					$mem_id = $this->request->data['created_by'];
					$member = $this->MembershipPayment->GymMember->find()->where(["id"=>$mem_id])->hydrate(false)->toArray();

					require_once(ROOT . DS .'vendor' . DS  . 'stripe-php' . DS . 'init.php');
					require_once(ROOT . DS .'vendor' . DS  . 'stripe-php' . DS . 'stripe-key' . DS .'stripe-key.php');

					$stripe = array(
						"secret_key" => $this->GYMFunction->getSettings('stripe_secret_key'),
						"publishable_key" => $this->GYMFunction->getSettings('stripe_publishable_key') 
					);

					\Stripe\Stripe::setApiKey($stripe['secret_key']);

					$name = $member[0]['first_name'].''.$member[0]['last_name'];
					$city = $member[0]['city'];
					$address = $member[0]['address'];
					$zipcode = $member[0]['zipcode'];
					$state = $member[0]['state'];
					$country = $this->GYMFunction->getSettings('country');

					$customer = \Stripe\Customer::create([
							'name' => $name,
					        'description' => 'test payment', 
					        'email' => $email,
						    'source'  => $token,
						  	"address" => [
						  	"city" => $city, 
						  	"country" => $country, 
						  	"line1" => $address, 
						  	"line2" => "", 
						  	"postal_code" => $zipcode, 
						  	"state" => $state
						  ]
					  ]);
						
					$currency = $this->GYMFunction->getSettings("currency");
					$customer_id = $customer->sources->data[0]->customer;
					$price = $this->request->data['amount'] * 100;
					//$customer_id.'_'.$this->request->data['mp_id']

					  $charge = \Stripe\Charge::create([
					      'customer' => $customer->id,
					      'amount'   => $price,
					      'currency' => $currency,
					      'description' => $customer_id.'_'.$this->request->data['mp_id'],
					  ]);

					  $chargeJson = $charge->jsonSerialize();
					  
					  if($chargeJson['amount_refunded'] == 0 && 
					  		empty($chargeJson['failure_code']) &&
					  		$chargeJson['paid'] == 1 &&
					  		$chargeJson['captured'] == 1
					  	) {

						$row->paid_amount = $row->paid_amount + $this->request->data["amount"];
						$this->MembershipPayment->save($row);

						$hrow = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
						$data['mp_id'] = $this->request->data['mp_id'];
						$data['amount'] = $chargeJson['amount']/100;
						$data['payment_method'] = $this->request->data["payment_method"];
						$data['paid_by_date'] = date("Y-m-d");
						$data['created_by'] = $session["id"];
						$data['trasaction_id'] = $chargeJson['balance_transaction'];
						
						$hrow = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($hrow,$data);						
						if($this->MembershipPayment->MembershipPaymentHistory->save($hrow))
						{
							$this->Flash->success(__("Success! Payment Added Successfully."));
						}
						//debug($chargeJson);die;
					  }
				}
			}			
			else if($this->request->data["payment_method"] == "Paypal" && $session["role_name"] == "member")
			{				
				$mp_id = $this->request->data["mp_id"];
				$user_id = $row->member_id;
				$membership_id = $row->membership_id;
				$custom_var = $mp_id;			
				$user_info = $this->MembershipPayment->GymMember->get($user_id);
				
				$new_session->write("Payment.mp_id",$mp_id);
				$new_session->write("Payment.amount",$this->request->data["amount"]);
				
				require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'paypal_process.php');
			}
			else{
				$row->paid_amount = $row->paid_amount + $this->request->data["amount"];
				$this->MembershipPayment->save($row);
				
				$hrow = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
				$data['mp_id'] = $mp_id;
				$data['amount'] = $this->request->data["amount"];
				$data['payment_method'] = $this->request->data["payment_method"];
				$data['paid_by_date'] = date("Y-m-d");
				$data['created_by'] = $session["id"];
				$data['transaction_id'] = "";
				
				$hrow = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($hrow,$data);						
				if($this->MembershipPayment->MembershipPaymentHistory->save($hrow))
				{
					$this->Flash->success(__("Success! Payment Added Successfully."));
				}
			}
			return $this->redirect(["action"=>"paymentList"]);
		}
    }
	
	public function generatePaymentInvoice()
	{
		$this->set("edit",false);
		$members = $this->MembershipPayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);
		
		$membership = $this->MembershipPayment->Membership->find("list",["keyField"=>"id","valueField"=>"membership_label"]);
		$this->set("membership",$membership);
		
		if($this->request->is('post'))
		{			
			$mid = $this->request->data["user_id"];
			
			$start_date = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_from']);
			
			$end_date = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_to']);
			$row = $this->MembershipPayment->newEntity();
			$pdata["member_id"] = $mid;
			$pdata["membership_id"] = $this->request->data["membership_id"];
			$pdata["membership_amount"] = $this->request->data["membership_amount"];
			$pdata["paid_amount"] = 0;
			$pdata["start_date"] = $start_date;
			$pdata["end_date"] = $end_date;
			$pdata["membership_status"] = "Continue";
			$pdata["payment_status"] = 0;
			$pdata["created_date"] = date("Y-m-d");
			$row = $this->MembershipPayment->patchEntity($row,$pdata);
			$this->MembershipPayment->save($row);			
			################## MEMBER's Current Membership Change ##################
			$member_data = $this->MembershipPayment->GymMember->get($mid);
			$member_data->selected_membership = $this->request->data["membership_id"];
			$member_data->membership_valid_from = $start_date;
			$member_data->membership_valid_to = $end_date;
			$this->MembershipPayment->GymMember->save($member_data);
			#####################Add Membership History #############################
			$mem_histoty = $this->MembershipPayment->MembershipHistory->newEntity();
			$hdata["member_id"] = $mid;
			$hdata["selected_membership"] = $this->request->data["membership_id"];
			$hdata["membership_valid_from"] = $start_date;
			$hdata["membership_valid_to"] = $end_date;
			$hdata["created_date"] = date("Y-m-d");
			$hdata = $this->MembershipPayment->MembershipHistory->patchEntity($mem_histoty,$hdata);
			if($this->MembershipPayment->MembershipHistory->save($mem_histoty))
			{
				$this->Flash->success(__("Success! Payment Added Successfully."));	
				return $this->redirect(["action"=>"paymentList"]);
			}
		}
	}
	
	public function membershipEdit($eid)
    {
		$this->set("edit",true);
		$members = $this->MembershipPayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);
		
		$membership = $this->MembershipPayment->Membership->find("list",["keyField"=>"id","valueField"=>"membership_label"]);
		$this->set("membership",$membership);
				
		$data = $this->MembershipPayment->get($eid);
		$this->set("data",$data->toArray());
		
		if($this->request->is("post"))
		{					
			$mid = $this->request->data["user_id"];
			//$start_date = date("Y-m-d",strtotime($this->request->data["membership_valid_from"]));
			$start_date = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_from']);
			$end_date = $this->GYMFunction->get_db_format_date($this->request->data['membership_valid_to']);
			//$end_date = date("Y-m-d",strtotime($this->request->data["membership_valid_to"]));
		
			$row = $this->MembershipPayment->get($eid);
			$row->member_id = $mid;
			$row->membership_id = $this->request->data["membership_id"];
			$row->membership_amount= $this->request->data["membership_amount"];
			$row->paid_amount = 0;
			$row->start_date = $start_date;
			$row->end_date = $end_date;
			$row->membership_status = "Continue";
			$this->MembershipPayment->save($row);
			###############################################################
			$member_data = $this->MembershipPayment->GymMember->get($mid);
			$member_data->selected_membership = $this->request->data["membership_id"];
			$member_data->membership_valid_from = $start_date;
			$member_data->membership_valid_to = $end_date;
			$this->MembershipPayment->GymMember->save($member_data);
			###########################################################
			$this->Flash->success(__("Success! Record Updated Successfully."));	
			return $this->redirect(["action"=>"paymentList"]);
		}
		$this->render("generatePaymentInvoice");		
    }
	
	public function deletePayment($mp_id)
	{
		$row = $this->MembershipPayment->get($mp_id);
		if($this->MembershipPayment->delete($row))
		{
			$this->Flash->success(__("Success! Payment Record Deleted Successfully."));	
			return $this->redirect(["action"=>"paymentList"]);
		}	
	}
	
	public function incomeList()
    {
		$data = $this->MembershipPayment->GymIncomeExpense->find("all")->contain(["GymMember"])->where(["invoice_type"=>"income"])->hydrate(false)->toArray();
	
		
		$this->set("data",$data);	
    }
	
	public function addIncome()
    {
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$members = $this->MembershipPayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);		
		
		if($this->request->is("post"))
		{	
			$row = $this->MembershipPayment->GymIncomeExpense->newEntity();
			$data = $this->request->data;
			
			$total_amount = null;
			foreach($data["income_amount"] as $amount)
			{$total_amount += $amount;}
			$data["total_amount"] = $total_amount;
			$data["entry"] = $this->get_entry_records($data);
			$data["receiver_id"] = $session["id"] ;//current userid;			
			$data["invoice_date"] = date("Y-m-d");	
			$data["invoice_date"] = $this->GYMFunction->get_db_format_date($data['invoice_date']);	

			$row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row,$data);			
			if($this->MembershipPayment->GymIncomeExpense->save($row))
			{
				$this->Flash->success(__("Success! Record Saved Successfully."));	
				return $this->redirect(["action"=>"incomeList"]);
			}
		}
    }
	
	public function get_entry_records($data)
	{
		$all_income_entry=$data['income_entry'];
		$all_income_amount=$data['income_amount'];
		
		$entry_data=array();
		$i=0;
		foreach($all_income_entry as $one_entry)
		{
			$entry_data[]= array('entry'=>$one_entry,
						'amount'=>$all_income_amount[$i]);
				$i++;
		}
		return json_encode($entry_data);
	}
	
	public function incomeEdit($eid)
	{
		$this->set("edit",true);
		$members = $this->MembershipPayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);
		
		$row = $this->MembershipPayment->GymIncomeExpense->get($eid);
		$this->set("data",$row->toArray());
		
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			$total_amount = null;
			foreach($data["income_amount"] as $amount)
			{$total_amount += $amount;}
			$data["total_amount"] = $total_amount;
			$data["entry"] = $this->get_entry_records($data);				
			$data["invoice_date"] = date("Y-m-d");	
			$data["invoice_date"] = $this->GYMFunction->get_db_format_date($data['invoice_date']);		
			
			$row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row,$data);	
			if($this->MembershipPayment->GymIncomeExpense->save($row))
			{
				$this->Flash->success(__("Success! Record Updated Successfully."));	
				return $this->redirect(["action"=>"incomeList"]);
			}
		}
		$this->render("addIncome");
	}
	
	public function deleteIncome($did)
    {
		$row = $this->MembershipPayment->GymIncomeExpense->get($did);
		if($this->MembershipPayment->GymIncomeExpense->delete($row))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully."));	
			return $this->redirect($this->referer());
		}	
    }
	
	public function printInvoice()
	{
		$this->loadComponent("GYMFunction");
		
		$id = $this->request->params["pass"][0];
		
		$invoice_type = $this->request->params["pass"][1];	
		
		$in_ex_table = TableRegistry::get("GymIncomeExpense");
		$setting_tbl = TableRegistry::get("GeneralSetting");
		$pay_history_tbl = TableRegistry::get("MembershipPaymentHistory");	
		
		$income_data = array();
		$expense_data = array();
		$invoice_data = array();
		
		$sys_data = $setting_tbl->find()->select(["name","address","gym_logo","date_format","office_number","country"])->hydrate(false)->toArray();
		
		if($invoice_type == "invoice")
		{
			$invoice_data = $this->MembershipPayment->find("all")->contain(["GymMember","Membership"])->where(["mp_id"=>$id])->hydrate(false)->toArray();
			
			$history_data = $pay_history_tbl->find("all")->where(["mp_id"=>$id])->hydrate(false)->toArray();
			$invoice_no = $pay_history_tbl->find("all")->last()->toArray();
			
			//debug($invoice_no['payment_history_id']);
			$session = $this->request->session();
			$float_l = ($session->read("User.is_rtl") == "1") ? "right" : "left";
			$float_r = ($session->read("User.is_rtl") == "1") ? "left" : "right";
			
			//$this->set("invoice_no",$invoice_no['payment_history_id']);
			$this->set("invoice_no",$id);
			$this->set("invoice_data",$invoice_data[0]);
			$this->set("history_data",$history_data);
			
		}
		else if($invoice_type == "income")
		{
			$income_data = $this->MembershipPayment->GymIncomeExpense->find("all")->contain(["GymMember"])->where(["GymIncomeExpense.id"=>$id])->hydrate(false)->toArray();
			$this->set("income_data",$income_data[0]);		
			$this->set("expense_data",$expense_data);
			$this->set("invoice_data",$invoice_data);
		}
		else if($invoice_type == "expense")
		{
			$expense_data = $this->MembershipPayment->GymIncomeExpense->find("all")->where(["GymIncomeExpense.id"=>$id])->select($this->MembershipPayment->GymIncomeExpense);
			$expense_data = $expense_data->leftjoin(["GymMember"=>"gym_member"],
									["GymIncomeExpense.receiver_id = GymMember.id"])->select($this->MembershipPayment->GymMember)->hydrate(false)->toArray();
			$expense_data[0]["gym_member"] = $expense_data[0]["GymMember"];
			unset($expense_data[0]["GymMember"]);	
			$this->set("income_data",$income_data);		
			$this->set("expense_data",$expense_data[0]);
			$this->set("invoice_data",$invoice_data);			
		}
		
		$this->set("sys_data",$sys_data[0]);
		
    }
	
	public function expenseList()
    {
		$data = $this->MembershipPayment->GymIncomeExpense->find("all")->where(["invoice_type"=>"expense"])->hydrate(false)->toArray();
		$this->set("data",$data);
    }
	
	public function addExpense()
    {
		$this->set("edit",false);		
		$session = $this->request->session()->read("User");
		
		if($this->request->is("post"))
		{	
			$row = $this->MembershipPayment->GymIncomeExpense->newEntity();
			$data = $this->request->data;
			$total_amount = null;
			foreach($data["income_amount"] as $amount)
			{$total_amount += $amount;}
			$data["total_amount"] = $total_amount;
			$data["entry"] = $this->get_entry_records($data);
			$data["receiver_id"] = $session["id"] ;//current userid;			
			//$data["invoice_date"] = date("Y-m-d",strtotime($data["invoice_date"]));	
			$data["invoice_date"] = $this->GYMFunction->get_db_format_date($this->request->data['invoice_date']);	
			$row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row,$data);			
			if($this->MembershipPayment->GymIncomeExpense->save($row))
			{
				$this->Flash->success(__("Success! Record Saved Successfully."));	
				return $this->redirect(["action"=>"expenseList"]);
			}
		}
    }
	
	public function expenseEdit($eid)
    {
		$this->set("edit",true);		
		
		$row = $this->MembershipPayment->GymIncomeExpense->get($eid);
		$this->set("data",$row->toArray());
		
		if($this->request->is("post"))
		{
			$data = $this->request->data;
			$total_amount = null;
			foreach($data["income_amount"] as $amount)
			{$total_amount += $amount;}
			$data["total_amount"] = $total_amount;
			$data["entry"] = $this->get_entry_records($data);				
			$data["invoice_date"] = $this->GYMFunction->get_db_format_date($this->request->data['invoice_date']);	
			
			$row = $this->MembershipPayment->GymIncomeExpense->patchEntity($row,$data);	
			if($this->MembershipPayment->GymIncomeExpense->save($row))
			{
				$this->Flash->success(__("Success! Record Updated Successfully."));	
				return $this->redirect(["action"=>"expenseList"]);
			}
		}
		$this->render("addExpense");
    }
	
	public function deleteAccountant($id)
	{
		$row = $this->GymAccountant->GymMember->get($id);
		if($this->GymAccountant->GymMember->delete($row))
		{
			$this->Flash->success(__("Success! Accountant Deleted Successfully."));
			return $this->redirect($this->referer());
		}
	}
	
	public function paymentSuccess()
	{
		$payment_data = $this->request->session()->read("Payment");
		$session = $this->request->session()->read("User");
		$feedata['mp_id']=$payment_data["mp_id"];
		$feedata['amount']=$payment_data['amount'];
		$feedata['payment_method']='Paypal';		
		$feedata['paid_by_date']=date("Y-m-d");		
		$feedata['created_by']=$session["id"];
		$row = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
		$row = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($row,$feedata);
		if($this->MembershipPayment->MembershipPaymentHistory->save($row))
		{
			$row = $this->MembershipPayment->get($payment_data["mp_id"]);
			$row->paid_amount = $row->paid_amount + $payment_data['amount'];
			$this->MembershipPayment->save($row);
		}
		
		$session = $this->request->session();
		$session->delete('Payment');
		
		$this->Flash->success(__("Success! Payment Successfully Completed."));
		return $this->redirect(["action"=>"paymentList"]);
	}
	
	public function ipnFunction()
	{
		if($this->request->is("post"))
		{
			$trasaction_id  = $_POST["txn_id"];
			$custom_array = explode("_",$_POST['custom']);
			$feedata['mp_id']=$custom_array[1];
			$feedata['amount']=$_POST['mc_gross_1'];
			$feedata['payment_method']='Paypal';	
			$feedata['trasaction_id']=$trasaction_id ;
			$feedata['created_by']=$custom_array[0];
			//$log_array		= print_r($feedata, TRUE);
			//wp_mail( 'bhaskar@dasinfomedia.com', 'gympaypal', $log_array);
			$row = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
			$row = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($row,$feedata);
			if($this->MembershipPayment->MembershipPaymentHistory->save($row))
			{
				$this->Flash->success(__("Success! Payment Successfully Completed."));
			}
			else{
				$this->Flash->error(__("Paypal Payment IPN save failed to DB."));
			}
			return $this->redirect(["action"=>"paymentList"]);
			//require_once SMS_PLUGIN_DIR. '/lib/paypal/paypal_ipn.php';
		}
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["paymentList","paymentSuccess","ipnFunction"];
		$staff_actions = ["paymentList","addIncome","incomeList","expenseList","addExpense","incomeEdit","expenseEdit"];
		$acc_actions = ["paymentList","addIncome","incomeList","expenseList","addExpense","incomeEdit","expenseEdit","printInvoice","deleteIncome"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			/*CASE "staff_member":
				if(in_array($curr_action,$staff_actions))
				{return true;}else{ return false;}
			break;*/
			
			CASE "accountant":
				if(in_array($curr_action,$acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}
}
