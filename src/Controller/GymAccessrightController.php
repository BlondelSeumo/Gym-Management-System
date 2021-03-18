<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class GymAccessrightController extends AppController
{
	public function accessRight()
    {
		$rights = $this->GymAccessright->find("all")->hydrate(false)->toArray();
		foreach($rights as $right)
		{
			$menu = $right["menu"];
			$member[$menu] = $right["member"];
			$staff_member[$menu] = $right["staff_member"];
			$accountant[$menu] = $right["accountant"];
		}
		
		$this->set("member",$member);
		$this->set("staff_member",$staff_member);
		$this->set("accountant",$accountant);
		if($this->request->is("post"))
		{			
			$request = $this->request->data;
			$access_right = array();
			$img_path = $this->request->base ."/webroot/img/icon/";
			$site_path = $this->request->base;
			
			//---------NEW MENU LINK START------------------ 
			$access_right['staff_member'] = array('menu_icone'=>'staff-member.png' ,'menu_title'=>'Staff Member',	
			'member' => isset($request['member_staff_member'])?$request['member_staff_member']:0,
			'action'=>'',
			'staff_member' =>isset($request['staff_member_staff_member'])?$request['staff_member_staff_member']:0,
			'accountant' =>isset($request['accountant_staff_member'])?$request['accountant_staff_member']:0,
			'page_link'=>$site_path.'/staff-members/staff-list',
			'controller'=>"StaffMembers");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['membership'] =array('menu_icone'=>'membership-type.png' ,'menu_title'=>'Membership Type',
			'action'=>'',
			'member' => isset($request['member_membership'])?$request['member_membership']:0,
			'staff_member' =>isset($request['staff_member_membership'])?$request['staff_member_membership']:0,
			'accountant' =>isset($request['accountant_membership'])?$request['accountant_membership']:0,
			'page_link'=>$site_path.'/membership/membership-list',
			'controller'=>"Membership");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['group'] =array('menu_icone'=>'group.png' ,'menu_title'=>'Group',
			'action'=>'',
			'member' => isset($request['member_group'])?$request['member_group']:0,
			'staff_member' =>isset($request['staff_member_group'])?$request['staff_member_group']:0,
			'accountant' =>isset($request['accountant_group'])?$request['accountant_group']:0,
			'page_link'=>$site_path.'/gym-group/group-list',
			'controller'=>"GymGroup");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['member'] =array('menu_icone'=>'member.png' ,'menu_title'=>'Member',
			'action'=>'',
			'member' => isset($request['member_member'])?$request['member_member']:0,
			'staff_member' =>isset($request['staff_member_member'])?$request['staff_member_member']:0,
			'accountant' =>isset($request['accountant_member'])?$request['accountant_member']:0,
			'page_link'=>$site_path.'/gym-member/member-list',
			'controller'=>"GymMember");
			
			//---------NEW MENU LINK START------------------ 
					
			//---------NEW MENU LINK START------------------ 
			$access_right['activity'] =array('menu_icone'=>'activity.png' ,'menu_title'=>'Activity',
			'action'=>'',
			'member' => isset($request['member_activity'])?$request['member_activity']:0,
			'staff_member' =>isset($request['staff_member_activity'])?$request['staff_member_activity']:0,
			'accountant' =>isset($request['accountant_activity'])?$request['accountant_activity']:0,
			'page_link'=>$site_path.'/activity/activity-list',
			'controller'=>"Activity");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['class-schedule'] =array('menu_icone'=>'class-schedule.png' ,'menu_title'=>'Class Schedule',
			'action'=>'',
			'member' => isset($request['member_class-schedule'])?$request['member_class-schedule']:0,
			'staff_member' =>isset($request['staff_member_class-schedule'])?$request['staff_member_class-schedule']:0,
			'accountant' =>isset($request['accountant_class-schedule'])?$request['accountant_class-schedule']:0,
			'page_link'=>$site_path.'/class-schedule/class-list',
			'controller'=>"ClassSchedule");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['class-booking'] =array('menu_icone'=>'class-schedule.png' ,'menu_title'=>'Class Booking',
			'action'=>'',
			'member' => isset($request['member_class-booking'])?$request['member_class-booking']:0,
			'staff_member' =>isset($request['staff_member_class-booking'])?$request['staff_member_class-booking']:0,
			'accountant' =>isset($request['accountant_class-booking'])?$request['accountant_class-booking']:0,
			'page_link'=>$site_path.'/class-booking/booking-list',
			'controller'=>"ClassBooking");

			//---------NEW MENU LINK START------------------ 
			$access_right['attendance'] =array('menu_icone'=>'attendance.png' ,'menu_title'=>'Attendance',
			'action'=>'',
			'member' => isset($request['member_attendence'])?$request['member_attendence']:0,
			'staff_member' =>isset($request['staff_member_attendence'])?$request['staff_member_attendence']:0,
			'accountant' =>isset($request['accountant_attendence'])?$request['accountant_attendence']:0,
			'page_link'=>$site_path.'/gym-attendance/attendance',
			'controller'=>"GymAttendance");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['assign-workout'] =array('menu_icone'=>'assigne-workout.png' ,'menu_title'=>'Assigned Workouts',
			'action'=>'',
			'member' => isset($request['member_assign-workout'])?$request['member_assign-workout']:0,
			'staff_member' =>isset($request['staff_member_assign-workout'])?$request['staff_member_assign-workout']:0,
			'accountant' =>isset($request['accountant_assign-workout'])?$request['accountant_assign-workout']:0,
			'page_link'=>$site_path.'/gym-assign-workout/workout-log',
			'controller'=>"GymAssignWorkout");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['workouts'] =array('menu_icone'=>'workout.png' ,'menu_title'=>'Workouts',
			'member' => isset($request['member_workouts'])?$request['member_workouts']:0,
			'action'=>'',
			'staff_member' =>isset($request['staff_member_workouts'])?$request['staff_member_workouts']:0,
			'accountant' =>isset($request['accountant_workouts'])?$request['accountant_workouts']:0,
			'page_link'=>$site_path.'/gym-daily-workout/workout-list',
			'controller'=>"GymDailyWorkout");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['accountant'] =array('menu_icone'=>'accountant.png' ,'menu_title'=>'Accountant',
			'action'=>'',
			'member' => isset($request['member_accountant'])?$request['member_accountant']:0,
			'staff_member' =>isset($request['staff_member_accountant'])?$request['staff_member_accountant']:0,
			'accountant' =>isset($request['accountant_accountant'])?$request['accountant_accountant']:0,
			'page_link'=>$site_path.'/gym-accountant/accountant-list',
			'controller'=>"GymAccountant");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['membership_payment'] =array('menu_icone'=>'fee.png' ,'menu_title'=>'Fee Payment',
			'action'=>'',
			'member' => isset($request['member_membership_payment'])?$request['member_membership_payment']:0,
			'staff_member' =>isset($request['staff_member_membership_payment'])?$request['staff_member_membership_payment']:0,
			'accountant' =>isset($request['accountant_membership_payment'])?$request['accountant_membership_payment']:0,
			'page_link'=>$site_path.'/membership-payment/payment-list',
			'controller'=>"MembershipPayment");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['income'] =array('menu_icone'=>'payment.png' ,'menu_title'=>'Income',
			'action'=>'',
			'member' => isset($request['member_income'])?$request['member_income']:0,
			'staff_member' =>isset($request['staff_member_income'])?$request['staff_member_income']:0,
			'accountant' =>isset($request['accountant_income'])?$request['accountant_income']:0,
			'page_link'=>$site_path.'/membership-payment/income-list',
			'controller'=>"MembershipPayment");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['expense'] =array('menu_icone'=>'payment.png' ,'menu_title'=>'Expense',
			'action'=>'',
			'member' => isset($request['member_expense'])?$request['member_expense']:0,
			'staff_member' =>isset($request['staff_member_expense'])?$request['staff_member_expense']:0,
			'accountant' =>isset($request['accountant_expense'])?$request['accountant_expense']:0,
			'page_link'=>$site_path.'/membership-payment/expense-list',
			'controller'=>"MembershipPayment");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['product'] =array('menu_icone'=>'products.png' ,'menu_title'=>'Product',
			'action'=>'',
			'member' => isset($request['member_product'])?$request['member_product']:0,
			'staff_member' =>isset($request['staff_member_product'])?$request['staff_member_product']:0,
			'accountant' =>isset($request['accountant_product'])?$request['accountant_product']:0,
			'page_link'=>$site_path.'/gym-product/product-list',
			'controller'=>"GymProduct");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['store'] =array('menu_icone'=>'store.png' ,'menu_title'=>'Store',
			'action'=>'',
			'member' => isset($request['member_store'])?$request['member_store']:0,
			'staff_member' =>isset($request['staff_member_store'])?$request['staff_member_store']:0,
			'accountant' =>isset($request['accountant_store'])?$request['accountant_store']:0,
			'page_link'=>$site_path.'/gym-store/sell-record',
			'controller'=>"GymStore");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['news_letter'] =array('menu_icone'=>'newsletter.png' ,'menu_title'=>'Newsletter',
			'action'=>'',
			'member' => isset($request['member_news_letter'])?$request['member_news_letter']:0,
			'staff_member' =>isset($request['staff_member_news_letter'])?$request['staff_member_news_letter']:0,
			'accountant' =>isset($request['accountant_news_letter'])?$request['accountant_news_letter']:0,
			'page_link'=>$site_path.'/gym-newsletter/setting',
			'controller'=>"GymNewsletter");
			
			//---------NEW MENU LINK START------------------ 
			$access_right['message'] =array('menu_icone'=>'message.png' ,'menu_title'=>'Message',
			'controller'=>"GymMessage",
			'action'=>'',
			'member' => isset($request['member_message'])?$request['member_message']:0,
			'staff_member' =>isset($request['staff_member_message'])?$request['staff_member_message']:0,
			'accountant' =>isset($request['accountant_message'])?$request['accountant_message']:0,
			'page_link'=>$site_path.'/gym-message/compose-message');
			
			//---------NEW MENU LINK START------------------ 
			$access_right['notice'] =array('menu_icone'=>'notice.png' ,'menu_title'=>'Notice',
			'controller'=>"GymNotice",
			'action'=>'',
			'member' => isset($request['member_notice'])?$request['member_notice']:0,
			'staff_member' =>isset($request['staff_member_notice'])?$request['staff_member_notice']:0,
			'accountant' =>isset($request['accountant_notice'])?$request['accountant_notice']:0,
			'page_link'=>$site_path.'/gym-notice/notice-list');
			
				//---------NEW MENU LINK START------------------ 
			$access_right['report'] =array('menu_icone'=>'report.png' ,'menu_title'=>'Report',
			'controller'=>"Report",
			'action'=>'',
			'member' => isset($request['member_report'])?$request['member_report']:0,
			'staff_member' =>isset($request['staff_member_report'])?$request['staff_member_report']:0,
			'accountant' =>isset($request['accountant_report'])?$request['accountant_report']:0,
			'page_link'=>$site_path.'/reports/membership-report');
			
			//---------NEW MENU LINK START------------------ 
			$access_right['nutrition'] =array('menu_icone'=>'nutrition-schedule.png' ,'menu_title'=>'Nutrition Schedule',
			'controller'=>"GymNutrition",
			'action'=>'',
			'member' => isset($request['member_nutrition'])?$request['member_nutrition']:0,
			'staff_member' =>isset($request['staff_member_nutrition'])?$request['staff_member_nutrition']:0,
			'accountant' =>isset($request['accountant_nutrition'])?$request['accountant_nutrition']:0,
			'page_link'=>$site_path.'/gym-nutrition/nutrition-list');
			
			//---------NEW MENU LINK START------------------ 
			$access_right['reservation'] =array('menu_icone'=>'reservation.png' ,'menu_title'=>'Event',
			'controller'=>"GymReservation",
			'action'=>'',
			'member' => isset($request['member_reservation'])?$request['member_reservation']:0,
			'staff_member' =>isset($request['staff_member_reservation'])?$request['staff_member_reservation']:0,
			'accountant' =>isset($request['accountant_reservation'])?$request['accountant_reservation']:0,
			'page_link'=>$site_path.'/gym-reservation/reservation-list');
			
			//---------NEW MENU LINK START------------------ 
			$access_right['account'] =array('menu_icone'=>'account.png' ,'menu_title'=>'Account',
			'controller'=>"GymProfile",
			'action'=>'',
			'member' => isset($request['member_account'])?$request['member_account']:0,
			'staff_member' =>isset($request['staff_member_account'])?$request['staff_member_account']:0,
			'accountant' =>isset($request['accountant_account'])?$request['accountant_account']:0,
			'page_link'=>$site_path.'/GymProfile/view_profile');
			
			$access_right['subscription_history'] =array('menu_icone'=>'subscription_history.png' ,'menu_title'=>'Subscription History',
			'controller'=>"GymSubscriptionHistory",
			'action'=>'',
			'member' => isset($request['member_subscription_history'])?$request['member_subscription_history']:0,
			'staff_member' =>isset($request['staff_member_subscription_history'])?$request['staff_member_subscription_history']:0,
			'accountant' =>isset($request['accountant_subscription_history'])?$request['accountant_subscription_history']:0,
			'page_link'=>$site_path.'/GymSubscriptionHistory/');
			
			$rows = array();
			//debug($access_right);die;
			foreach($access_right as $menu=>$right)
			{
				$data = array();
				$data["menu"] = $menu;
				$data["controller"] = $right["controller"];
				$data["action"] = $right["action"];
				$data["menu_icon"] = $right["menu_icone"];
				$data["menu_title"] = $right["menu_title"];
				$data["member"] = $right["member"];
				$data["staff_member"] = $right["staff_member"];
				$data["accountant"] = $right["accountant"];
				$data["page_link"] = $right["page_link"];
				$rows[] = $data;
			}
			$conn = ConnectionManager::get('default');
			$sql = "TRUNCATE TABLE gym_accessright";
			$stmt = $conn->execute($sql);	
			
			$entities = $this->GymAccessright->newEntities($rows);
			foreach($entities as $entity)
			{
				if($this->GymAccessright->save($entity))
				{$success = true;}else{$success = false;}
			}
			if($success)
			{
				$this->Flash->success(__("Success! Settings Saved successfully."));
				return $this->redirect(["action"=>"accessRight"]);
			}				
		}
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = [];
		$staff__acc_actions = [];
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

