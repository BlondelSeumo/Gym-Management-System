<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Controller\DateTime;
use GoogleCharts;

class DashboardController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("GYMFunction");
		require_once(ROOT . DS .'vendor' . DS  . 'chart' . DS . 'GoogleCharts.class.php');		
	}
	public function index()
	{	
		$session = $this->request->session()->read("User");
		switch($session["role_name"])
		{
			CASE "administrator":
				return $this->redirect(["action"=>"adminDashboard"]);
			break;
			CASE "member":
				return $this->redirect(["action"=>"memberDashboard"]);
			break;
			default:	
				return $this->redirect(["action"=>"staffAccDashboard"]);
		}		
	}
	
	public function adminDashboard()
	{
		$session = $this->request->session()->read("User");
		$conn = ConnectionManager::get('default');
		$this->autoRender = false;
		$mem_table = TableRegistry::get("GymMember");
		$grp_tbl = TableRegistry::get("GymGroup");
		$message_tbl = TableRegistry::get("GymMessage");
		$membership_tbl = TableRegistry::get("Membership");				
		$notice_tbl = TableRegistry::get("gym_notice");		
		
		$members = $mem_table->find("all")->where(["role_name"=>"member"]);
		$members = $members->count();
		
		$staff_members = $mem_table->find("all")->where(["role_name"=>"staff_member"]);
		$staff_members = $staff_members->count();
		
		$curr_id = intval($session["id"]);
		$messages = $message_tbl->find("all")->where(["receiver"=>$curr_id]);
		$messages = $messages->count();
		
		$groups = $grp_tbl->find("all");
		$groups = $groups->count();
		
		$membership = $membership_tbl->find("all")->limit(5)->select(["membership_label","gmgt_membershipimage"])->hydrate(false)->toArray();
		$groups_data = $grp_tbl->find("all")->limit(5)->select(["name","image"])->hydrate(false)->toArray();
		
		$cal_lang = $this->GYMFunction->getSettings("calendar_lang");
		
		$this->set("cal_lang",$cal_lang);
		$this->set("members",$members);
		$this->set("staff_members",$staff_members);
		$this->set("messages",$messages);
		$this->set("groups",$groups);
		$this->set("membership",$membership);
		$this->set("groups_data",$groups_data);
		
		################################################
		
		$month =array('1'=>"January",'2'=>"February",'3'=>"March",'4'=>"April",
		'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",
		'9'=>"September",'10'=>"Octomber",'11'=>"November",'12'=>"December",);
		$year = date('Y');
		
		/* $q="SELECT EXTRACT(MONTH FROM created_date) as date_d,sum(paid_amount) as count_c FROM `membership_payment` WHERE YEAR(created_date) = '".$year."' group by month(created_date) ORDER BY month(created_date) ASC";    NOT WORKING ON MYSQL 5.7/PHP 5.7*/
		$q="SELECT EXTRACT(MONTH FROM created_date) as date_d,sum(paid_amount) as count_c FROM `membership_payment` WHERE YEAR(created_date) = '".$year."' group by date_d ORDER BY date_d ASC";
				
		$result = $conn->execute($q);
		$result = $result->fetchAll('assoc');		
		$chart_array_pay = array();
		$chart_array_pay[] = array('Month',__('Fee Payment'));
		foreach($result as $r)
		{

			$chart_array_pay[]=array( $month[$r["date_d"]],(int)$r["count_c"]);
		}
		$this->set("chart_array_pay",$chart_array_pay); 
		$this->set("result_pay",$result); 
		
		
		
		
		
		################################################	
		
		$chart_array = array();
		$report_2 ="SELECT  at.class_id,cl.class_name,
					SUM(case when `status` ='Present' then 1 else 0 end) as Present,
					SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
					from `gym_attendance` as at,`class_schedule` as cl where at.attendance_date >  DATE_SUB(NOW(), INTERVAL 1 WEEK) AND at.class_id = cl.id  AND at.role_name = 'member' GROUP BY at.class_id";
		$report_2 = $conn->execute($report_2);
		$report_2 = $report_2->fetchAll('assoc');			
		$report_2 = $report_2;
		$chart_array_at[] = array(__('Class'),__('Present'),__('Absent'));	
		if(!empty($report_2))
		{
			foreach($report_2 as $result)
			{			
				$cls = $result['class_name'];					
				$chart_array_at[] = [$result['class_name'],(int)$result["Present"],(int)$result["Absent"]];
			}
		}
		$this->set("report_member",$report_2); 
		$this->set("chart_array_at",$chart_array_at);

		##################STAFF ATTENDANCE REPORT#############################	
	
		$report_2 = null;
		
		$chart_array_staff = array();
		$report_2 ="SELECT  at.user_id,
				SUM(case when `status` ='Present' then 1 else 0 end) as Present,
				SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
				from `gym_attendance` as at where at.attendance_date >  DATE_SUB(NOW(), INTERVAL 1 WEEK)  AND at.role_name = 'staff_member' GROUP BY at.user_id";
		
		$report_2 = $conn->execute($report_2);
		$report_2 = $report_2->fetchAll('assoc');
		
		$chart_array_staff[] = array(__('Staff Member'),__('Present'),__('Absent'));
		if(!empty($report_2))
		{
			foreach($report_2 as $result)
			{
				$user_name = $this->GYMFunction->get_user_name($result["user_id"]);
				$chart_array_staff[] = array("$user_name",(int)$result["Present"],(int)$result["Absent"]);
			}
		} 			
		$this->set("chart_array_staff",$chart_array_staff);
		$this->set("report_sataff",$report_2);
	
		$cal_array = $this->getCalendarData();
		$this->set("cal_array",$cal_array);		
		
		$this->GYMFunction->membershipExpiredReminder();
		$this->render("dashboard");
	}
	
	public function memberDashboard()
	{
		$session = $this->request->session()->read("User");
		$uid = intval($session["id"]);
		$conn = ConnectionManager::get('default');		
		$mem_table = TableRegistry::get("GymMember");
		$grp_tbl = TableRegistry::get("GymGroup");
		$message_tbl = TableRegistry::get("GymMessage");
		$membership_tbl = TableRegistry::get("Membership");		
		$res_tbl = TableRegistry::get("gym_reservation");		
		$notice_tbl = TableRegistry::get("gym_notice");		
		
		$members = $mem_table->find("all")->where(["role_name"=>"member","id"=>$uid]);
		$members = $members->count();
		
		$staff_members = $mem_table->find("all")->where(["role_name"=>"staff_member"]);
		$staff_members = $staff_members->count();
		
		$curr_id = $uid;
		$messages = $message_tbl->find("all")->where(["receiver"=>$curr_id]);
		$messages = $messages->count();
		
		$groups = $grp_tbl->find("all");
		$groups = $groups->count();
		
		$membership = $membership_tbl->find("all")->limit(5)->select(["membership_label","gmgt_membershipimage"])->hydrate(false)->toArray();
		$groups_data = $grp_tbl->find("all")->limit(5)->select(["name","image"])->hydrate(false)->toArray();
		
		$cal_array = $this->getCalendarData();
		
		$cal_lang = $this->GYMFunction->getSettings("calendar_lang");
		
		$this->set("cal_lang",$cal_lang);
		$this->set("cal_array",$cal_array);
		$this->set("members",$members);
		$this->set("staff_members",$staff_members);
		$this->set("messages",$messages);
		$this->set("groups",$groups);
		$this->set("membership",$membership);
		$this->set("groups_data",$groups_data);
	
		$weight_data["data"] = $this->GYMFunction->generate_chart("Weight",$uid);
		$weight_data["option"] = $this->GYMFunction->report_option("Weight");
		$this->set("weight_data",$weight_data);
		
		$height_data["data"] = $this->GYMFunction->generate_chart("Height",$uid);
		$height_data["option"] = $this->GYMFunction->report_option("Height");
		$this->set("height_data",$height_data);		
		
		$thigh_data["data"] = $this->GYMFunction->generate_chart("Thigh",$uid);
		$thigh_data["option"] = $this->GYMFunction->report_option("Thigh");
		$this->set("thigh_data",$thigh_data);
		
		$chest_data["data"] = $this->GYMFunction->generate_chart("Chest",$uid);
		$chest_data["option"] = $this->GYMFunction->report_option("Chest");
		$this->set("chest_data",$chest_data);
		
		$waist_data["data"] = $this->GYMFunction->generate_chart("Waist",$uid);
		$waist_data["option"] = $this->GYMFunction->report_option("Waist");
		$this->set("waist_data",$waist_data);
		
		$arms_data["data"] = $this->GYMFunction->generate_chart("Arms",$uid);
		$arms_data["option"] = $this->GYMFunction->report_option("Arms");
		$this->set("arms_data",$arms_data);
		
		$fat_data["data"] = $this->GYMFunction->generate_chart("Fat",$uid);
		$fat_data["option"] = $this->GYMFunction->report_option("Fat");
		$this->set("fat_data",$fat_data);
		
		
	}
	
	public function staffAccDashboard()
	{
		$session = $this->request->session()->read("User");
		$uid = intval($session["id"]);
		$conn = ConnectionManager::get('default');		
		$mem_table = TableRegistry::get("GymMember");
		$grp_tbl = TableRegistry::get("GymGroup");
		$message_tbl = TableRegistry::get("GymMessage");
		$membership_tbl = TableRegistry::get("Membership");		
		$res_tbl = TableRegistry::get("gym_reservation");		
		$notice_tbl = TableRegistry::get("gym_notice");		
		
		if($session['role_name'] == "staff_member")
		{
		$members = $mem_table->find("all")->where(["role_name"=>"member","assign_staff_mem"=>$uid]);
		$members = $members->count();
		}
		else{
			$members = $mem_table->find("all")->where(["role_name"=>"member"]);
			$members = $members->count();
		}

		if($session['role_name'] == "staff_member")
		{
		$staff_members = $mem_table->find("all")->where(["role_name"=>"staff_member","id"=>$uid]);
		}
		else{
			$staff_members = $mem_table->find("all")->where(["role_name"=>"staff_member"]);
		}
		$staff_members = $staff_members->count();
		
		$curr_id = $uid;
		$messages = $message_tbl->find("all")->where(["receiver"=>$curr_id]);
		$messages = $messages->count();
		
		$groups = $grp_tbl->find("all");
		$groups = $groups->count();
		
		$membership = $membership_tbl->find("all")->limit(5)->select(["membership_label","gmgt_membershipimage"])->hydrate(false)->toArray();
		$groups_data = $grp_tbl->find("all")->limit(5)->select(["name","image"])->hydrate(false)->toArray();
		
		$cal_array = $this->getCalendarData();
		
		$cal_lang = $this->GYMFunction->getSettings("calendar_lang");
		
		$this->set("cal_lang",$cal_lang);
		$this->set("cal_array",$cal_array);
		$this->set("members",$members);
		$this->set("staff_members",$staff_members);
		$this->set("messages",$messages);
		$this->set("groups",$groups);
		$this->set("membership",$membership);
		$this->set("groups_data",$groups_data);
		
	}

	public function getCalendarData()
	{
		$session = $this->request->session()->read("User");
		$res_tbl = TableRegistry::get("gym_reservation");
		$mem_table = TableRegistry::get("GymMember");
		$grp_tbl = TableRegistry::get("GymGroup");
		$message_tbl = TableRegistry::get("GymMessage");
		$membership_tbl = TableRegistry::get("Membership");				
		$notice_tbl = TableRegistry::get("gym_notice");		
		
		$reservationdata = $res_tbl->find("all")->hydrate(false)->toArray();
		$cal_array = array();
		
		if(!empty($reservationdata))
		{
			foreach ($reservationdata as $retrieved_data){
				$start_time = str_ireplace([":AM",":PM"],[" AM"," PM"],$retrieved_data["start_time"]);
				$end_time = str_ireplace([":AM",":PM"],[" AM"," PM"],$retrieved_data["end_time"]);				
				$start_time = date("H:i:s", strtotime($start_time)); 
				$end_time = date("H:i:s", strtotime($end_time)); 
				
				$cal_array [] = array (
						'title' => $retrieved_data["event_name"],
						'start' => $retrieved_data["event_date"]->format("Y-m-d")."T{$start_time}",
						'end' => $retrieved_data["event_date"]->format("Y-m-d")."T{$end_time}",
						
				);
			}
		}
		
		$birthday_boys=$mem_table->find("all")->where(["role_name"=>"member","activated"=>1])->group("id")->hydrate(false)->toArray();
		$boys_list="";
		
		if (!empty($birthday_boys )) {
			foreach ( $birthday_boys as $boys ) {
				
				//$startdate = $boys["birth_date"]->format("Y");
				$startdate = date("Y",strtotime($boys["birth_date"]));
				$enddate = $startdate + 90;
				$years = range($startdate,$enddate,1);
				foreach($years as $year)
				{				
					$start = date('m-d',strtotime($boys['birth_date']));
					 $cal_array [] = array (
						'title' => $boys["first_name"]."'s Birthday",
						//'start' =>"{$year}-{$boys["birth_date"]->format("m-d")}",
						//'end' => "{$year}-{$boys["birth_date"]->format("m-d")}",
						'start' => "{$year}-{$start}",
						'end' => "{$year}-{$start}",
						'backgroundColor' => '#F25656');
				}
			}

		}
		##################################
		$all_notice = "";
		if($session["role_name"] == "administrator")
		{
			$all_notice = $notice_tbl->find("all")->hydrate(false)->toArray();
		}
		else{
			$all_notice = $notice_tbl->find("all")->where(["OR"=>[["notice_for"=>"all"],["notice_for"=>$session["role_name"]]]])->hydrate(false)->toArray();
		}
		
		if (! empty ( $all_notice )) {
			foreach ( $all_notice as $notice ) {
				$i=1;				
				$cal_array[] = array (
						'title' => $notice["notice_title"],
						'start' => $notice["start_date"]->format("Y-m-d"),
						'end' => date('Y-m-d',strtotime($notice["end_date"]->format("Y-m-d").' +'.$i.' days')),
						'color' => '#12AFCB'
				);	
				
			}
		}		
		return $cal_array;		
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$admin_actions = ["index","adminDashboard","memberDashboard","staffAccDashboard"];
		$members_actions = ["index","memberDashboard"];
		$staff_acc_actions = ["index","staffAccDashboard"];
		switch($role_name)
		{
			CASE "administrator":
				if(in_array($curr_action,$admin_actions))
				{return true;}else{return false;}
			break;
			
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