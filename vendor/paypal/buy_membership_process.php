<?php
$obj_membership_payment=new Gmgt_membership_payment;
$p 	= new Gmgt_paypal_class(); /* 
	$p->admin_mail 	= GMS_EMAIL_ADD;  set notification email
$action 		= $_REQUEST["fees_pay_id"]; */


if(isset($_REQUEST["buy_confirm_paypal"])){
	$user_id  = $_REQUEST["member_id"];
	$membership_id=$_REQUEST["membership_id"];
	$custom_var=$membership_id;
}
$user_info = get_userdata($user_id);
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$p->add_field('business', get_option('gmgt_paypal_email')); /* Call the facilitator eaccount */

$p->add_field('cmd', '_cart'); /* cmd should be _cart for cart checkout */
$p->add_field('upload', '1');
$referrer_ipn_success = array(				
				'action' => 'success'
				);
	$referrer_ipn_success = add_query_arg( $referrer_ipn_success, home_url() );
$p->add_field('return', $referrer_ipn_success); /* return URL after the transaction got over */

$p->add_field('cancel_return', home_url().'/?dashboard=user&page=membership_payment&action=cancel'); /* cancel URL if the trasaction was cancelled during half of the transaction */
$referrer_ipn = array(				
				'action' => 'ipn',
				'from'=>'buy_membership'
				);
	$referrer_ipn = add_query_arg( $referrer_ipn, home_url() );

$p->add_field('notify_url',$referrer_ipn); /* Notify URL which received IPN (Instant Payment Notification)*/
$p->add_field('currency_code', get_option( 'gmgt_currency_code' ));
$p->add_field('invoice', date("His").rand(1234, 9632));
$p->add_field('item_name_1', get_membership_name($membership_id));
$p->add_field('item_number_1', 4);
$p->add_field('quantity_1', 1);
$p->add_field('amount_1', get_membership_price($membership_id));

$p->add_field('first_name',$user_info->first_name);
$p->add_field('last_name', $user_info->last_name);
$p->add_field('address1',$user_info->address);
$p->add_field('city', $user_info->city_name);
$p->add_field('custom', $user_id."_".$custom_var);
	
		
$p->add_field('state', get_user_meta($user_id,'state_name',true));
$p->add_field('country', get_option( 'gmgt_contry' ));
$p->add_field('zip', get_user_meta($user_id,'zip_code',true));
$p->add_field('email',$user_info->user_email);
$p->submit_paypal_post();  

exit;
?>