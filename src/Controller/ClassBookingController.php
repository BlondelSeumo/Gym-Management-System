<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

Class ClassBookingController  extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
		$this->loadComponent("GYMFunction");	
		require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'paypal_class.php');	
	}
	
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);       
        $this->Auth->allow(['index','getClassFees','getClassDay','paymentSuccess']);
		if (in_array($this->request->action, ['getClassFees','getClassDay'])) {
			$this->eventManager()->off($this->Csrf);
		}
		 
    }
	
	public function index()
	{		
		$this->viewBuilder()->layout('login');

		$class_schedule = TableRegistry::get('class_schedule');
		$classes = $class_schedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->hydrate(false)->toArray();

		$this->set('class',$classes);
		$this->set("edit",false);

		if($this->request->is('post')){

			
			$data = $this->request->data;

			if($data['full_name'] != '' && $data['mobile_no'] != '' && $data['email'] != '' && $data['class_id'] != '' && $data['booking_amount'] != '' 
			 && $data['booking_date'] != '')
			{
				if($data['payment_by'] == 'Stripe'){

					if(!empty($this->request->data['stripeToken'])) {
					
						$token = $this->request->data['stripeToken'];
						$email = $this->request->data['stripeEmail'];

						//debug($data);die;
						require_once(ROOT . DS .'vendor' . DS  . 'stripe-php' . DS . 'init.php');

						$stripe = array(
								"secret_key" => $this->GYMFunction->getSettings('stripe_secret_key'),
								"publishable_key" => $this->GYMFunction->getSettings('stripe_publishable_key') 
							);

						\Stripe\Stripe::setApiKey($stripe['secret_key']);

						 $city = $this->request->data['city'];
						 $address = $this->request->data['address'];
						 $zipcode = $this->request->data['zipcode'];
						 $state = $this->request->data['state'];
						$country = $this->GYMFunction->getSettings('country');

						$customer = \Stripe\Customer::create([
									'name'=>$this->request->data['full_name'],
							        //'description' => 'test payment', 
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
						$price = ($this->request->data['booking_amount']) * 100;

						$charge = \Stripe\Charge::create([
							      'customer' => $customer->id,
							      'amount'   => $price,
							      'currency' => $currency,
							      'description' => 'Paid class booking'
							  ]);

						$chargeJson = $charge->jsonSerialize();

						if($chargeJson['amount_refunded'] == 0 && 
						  		empty($chargeJson['failure_code']) &&
						  		$chargeJson['paid'] == 1 &&
						  		$chargeJson['captured'] == 1
						  	) 
						{
							$row = $this->ClassBooking->newEntity();

							$data['full_name'] = $this->request->data['full_name'];
							$data['gender'] = $this->request->data['gender'];
							$data['mobile_no'] = $this->request->data['mobile_no'];
							$data['email'] = $this->request->data['email'];
							$data['address'] = $this->request->data['address'];
							$data['city'] = $this->request->data['city'];
							$data['zipcode'] = $this->request->data['zipcode'];
							$data['class_id'] = $this->request->data['class_id'];
							$data['booking_date'] = $this->GYMFunction->get_db_format_date($this->request->data['booking_date']);
							$data['booking_type'] = $this->request->data['booking_type'];
							$data['booking_amount'] = $chargeJson['amount'] / 100;
							$data['transaction_id'] = $chargeJson['balance_transaction'];
							$data['payment_by'] = $chargeJson['calculated_statement_descriptor'];
							$data['status'] = 'Paid';
							$data['created_at'] = date('Y-m-d');

							$row = $this->ClassBooking->patchEntity($row,$data);
							if($this->ClassBooking->save($row))
							{
								$this->Flash->success(__("Success! Record Saved Successfully."));
								return $this->redirect(["action"=>"index"]);
							} 
						}else{
							$this->Flash->error(__("Error! Something are wrong."));
							return $this->redirect(["action"=>"index"]);
						}
					}
				}else if($data['payment_by'] == 'Paypal'){
					
					$user_info = $this->request->data;
					
					$new_session = $this->request->session();
					//$new_session->write("Payment.user",$mp_id);
					$new_session->write("Payment",$this->request->data);

					require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'class_booking_paypal_process.php');
					//echo 'hello';die;
				}else{

					$email = $this->request->data['email'];
					$class_id = $this->request->data['class_id'];

					$booking = $this->ClassBooking->find()->where(['email'=>$email,'class_id'=>$class_id,'booking_type'=>'Demo'])->hydrate(false)->toArray();
					//debug($booking);die;
					if($booking != NULL){
						$this->Flash->error(__("Error! This class is already enjoy by you, now you can not book this class as demo."));
						return $this->redirect(["action"=>"index"]);
					}else{
						$row = $this->ClassBooking->newEntity();

						$this->request->data['booking_date'] = $this->GYMFunction->get_db_format_date($this->request->data['booking_date']);
						$this->request->data['created_at'] = date('Y-m-d');

						if($this->request->data['booking_type'] == 'Demo')
						{
							$this->request->data['booking_amount'] = '0';
						}else{
							$this->request->data['booking_amount'] = $this->request->data['booking_amount'];
						}
						$row = $this->ClassBooking->patchEntity($row,$this->request->data);
						if($this->ClassBooking->save($row))
						{
							$this->Flash->success(__("Success! Record Saved Successfully."));
							return $this->redirect(["action"=>"index"]);
						} 
					}
				}
			}else{
				$this->Flash->error(__("Error! Please Fill All Information."));
						return $this->redirect(["action"=>"index"]);
			}
		}
	}
	
	public function regComplete()
	{
		$this->autoRender = false;
		echo "<br><p><i><strong>Success!</strong> Registration completed successfully.</i></p>";
		echo "<p><i><a href='{$this->request->base}/Users'>Click Here</a> to Redirect on login page.</i></p>";
	}

	public function getClassFees(){
		$this->autoRender=false;

		if($this->request->is('ajax')){

			$class_schedule = TableRegistry::get('class_schedule');
			$class_id = $this->request->data['class_id'];

			$data = $class_schedule->find('all')->select(['class_fees'])->where(['id'=>$class_id])->hydrate(false)->toArray();

			echo $data[0]['class_fees'];
		}
		
	}

	public function getClassDay(){
		$this->autoRender=false;
		$class_schedule = TableRegistry::get('class_schedule_list');
		
		if($this->request->is('ajax')){
			$class_id = $this->request->data['class_id'];

			$data = $class_schedule->find('all')->select(['days'])->where(['class_id'=>$class_id])->hydrate(false)->toArray();

			/*foreach($data as $key => $value){
				$blocks[] = $value['days'];
				
			}*/
			$book_day = [];

			if(!empty($data)){
				$blocks1 = json_decode($data[0]['days']);

				foreach ($blocks1 as $key => $value) {
					
					$currnt_date = Date('Y-m-d');

					for($i=0;$i<=30;$i++){
						$date = strtotime("+".$i." days", strtotime($currnt_date));

						$day = date('l', $date);

						if($day == $value){
							
							$book_day[] = date('Y-m-d',$date);
						}
					}
					
				}

			}
			echo json_encode($book_day);
		}
	}
	public function bookingList(){
		$booking_tbl = TableRegistry::get('class_booking');

		//$booking_data = $booking_tbl->find()->hydrate(false)->toArray();
		$booking_data = $booking_tbl->find()
						->select(['class_booking.booking_id','class_booking.booking_date','class_schedule.class_name','class_schedule.location','class_booking.full_name','class_booking.booking_type','class_booking.booking_amount','class_booking.mobile_no'])
						->join([
							'table' => 'class_schedule',
							'type' => 'inner',
							'conditions' => 'class_booking.class_id = class_schedule.id'
						])->hydrate(false)->toArray();

						//debug($booking_data);die;
		$this->set('data',$booking_data);
		
	}

	public function paymentSuccess()
	{
		$payment_data = $this->request->session()->read("Payment");
		//$session = $this->request->session()->read("User");
		//$feedata['mp_id']=$payment_data["mp_id"];

		$row = $this->ClassBooking->newEntity();

		$payment_data['booking_date'] = $this->GYMFunction->get_db_format_date($payment_data['booking_date']);
		$payment_data['status'] = 'Paid';
		$payment_data['created_at'] = date('Y-m-d');

		$row = $this->ClassBooking->patchEntity($row,$payment_data);

		if($this->ClassBooking->save($row))
		{
			$session = $this->request->session();
			$session->delete('Payment');
			$this->Flash->success(__("Success! Record Saved Successfully."));
			return $this->redirect(["action"=>"index"]);
		}
		
	}

	public function ipnFunction()
	{
		if($this->request->is("post"))
		{
			
			// $trasaction_id  = $_POST["txn_id"];
			// $custom_array = explode("_",$_POST['custom']);
			// $feedata['mp_id']=$custom_array[1];
			// $feedata['amount']=$_POST['mc_gross_1'];
			// $feedata['payment_method']='Paypal';	
			// $feedata['trasaction_id']=$trasaction_id ;
			// $feedata['created_by']=$custom_array[0];
			// //$log_array		= print_r($feedata, TRUE);
			// //wp_mail( 'bhaskar@dasinfomedia.com', 'gympaypal', $log_array);
			// $row = $this->MembershipPayment->MembershipPaymentHistory->newEntity();
			// $row = $this->MembershipPayment->MembershipPaymentHistory->patchEntity($row,$feedata);
			// if($this->MembershipPayment->MembershipPaymentHistory->save($row))
			// {
			// 	$this->Flash->success(__("Success! Payment Successfully Completed."));
			// }
			// else{
			// 	$this->Flash->error(__("Paypal Payment IPN save failed to DB."));
			// }
			// return $this->redirect(["action"=>"paymentList"]);
			// //require_once SMS_PLUGIN_DIR. '/lib/paypal/paypal_ipn.php';
		}
	}
}