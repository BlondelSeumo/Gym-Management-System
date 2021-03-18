<?php
$p 	= new Gmgt_paypal_class();
$country = $this->GYMFunction->getSettings("country");
$paypal_email = $this->GYMFunction->getSettings("paypal_email");
$currency_code = $this->GYMFunction->getSettings("currency");
$enable_sandbox = $this->GYMFunction->getSettings("enable_sandbox");
$class_name = $this->GYMFunction->get_class_by_id($user_info['class_id']);

$success_url = "http://".$_SERVER["HTTP_HOST"].$this->request->base ."/ClassBooking/paymentSuccess";
$cancel_url = "http://".$_SERVER["HTTP_HOST"].$this->request->base ."/ClassBooking/index";
$ipn_url = "http://".$_SERVER["HTTP_HOST"].$this->request->base ."/ClassBooking/ipnFunction";
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

$p->add_field('business',$paypal_email);
$p->add_field('cmd', '_cart'); 
$p->add_field('upload', '1');
$p->add_field('return',$success_url); 
$p->add_field('cancel_return',$cancel_url); 
$p->add_field('notify_url', $ipn_url); 
$p->add_field('currency_code', $currency_code);
$p->add_field('invoice', date("His").rand(1234, 9632));
$p->add_field('item_name_1', $class_name);
$p->add_field('item_number_1', 4);
$p->add_field('quantity_1', 1);
$p->add_field('amount_1', $user_info['booking_amount']);
$p->add_field('full_name',$user_info['full_name']);
$p->add_field('address1',$user_info['address']);
$p->add_field('city', $user_info['city']);
//$p->add_field('custom', $user_id."_".$custom_var);			
$p->add_field('state', $user_info['state']);
$p->add_field('country', $country);
$p->add_field('zip', $user_info['zipcode']);
$p->add_field('email',$user_info['email']);
$p->submit_paypal_post($enable_sandbox);
exit;
?>