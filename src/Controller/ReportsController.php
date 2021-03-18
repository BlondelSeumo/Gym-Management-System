<?php

namespace App\Controller;

use Cake\App\Controller;

use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

use GoogleCharts;



class ReportsController extends AppController

{

	public function initialize()

	{

		parent::initialize();

		require_once(ROOT . DS .'vendor' . DS  . 'chart' . DS . 'GoogleCharts.class.php');		

	}

	public function qrReport(){

		$this->set("view",false);

		

		if($this->request->is("post")){

			

			$att_tbl = TableRegistry::get("gym_attendance");	

			$cls_tbl = TableRegistry::get("class_schedule");

			$sdate = $this->GYMFunction->get_db_format_date($this->request->data['sdate']);

			

			$conn = ConnectionManager::get('default');	

			

			//$report_2 = "SELECT  at.class_id,cl.class_name, at.scan_by from `gym_attendance` as at,`class_schedule` as cl where at.attendance_date = '{$sdate}' AND at.role_name = 'member'";

			

			$report_2 = "select gm.first_name,gm.last_name,gt.class_id,cs.class_name,gms.first_name as memname,gms.last_name as memlastname from gym_attendance gt INNER JOIN gym_member gm ON gt.scan_by = gm.id INNER JOIN class_schedule cs ON gt.class_id = cs.id INNER JOIN gym_member gms ON gt.user_id = gms.id WHERE gt.scan_date = '$sdate'";

			

			$report_2 = $conn->execute($report_2);

			$report_2 = $report_2->fetchAll('assoc');	

			//debug($report_2);die;

			$this->set("report_2",$report_2);  

			$this->set("sdate",$sdate);

			$this->set("view",true);

		}

	}

	public function membershipReport() {
		$membership_tbl = TableRegistry::get("Membership");		
		$member_ship_array = array();
		$chart_array = array();
		$chart_array[] = array('Membership',__('Number Of Member'));
		$data = $membership_tbl->find("all")->select(["Membership.membership_label"]);
		$data = $data->leftjoin(["GymMember"=>"gym_member"],["GymMember.selected_membership = Membership.id"])->select(["count"=>$data->func()->count("GymMember.id")])->group("Membership.id")->hydrate(false)->toArray();
		if(!empty($data)) {
			foreach($data as $result) {
				$chart_array[]=[$result["membership_label"],$result["count"]];
			}
		}
		$this->set("data",$data); 
		$this->set("chart_array",$chart_array); 
	}

	public function monthlyworkoutreport() {
		$mem_tbl = TableRegistry::get("GymMember");
		$members = $mem_tbl->find("list",["keyField"=>"id","valueField"=>function ($e) { return $e->first_name." ".$e->last_name;}])->where(["role_name"=>"member"]);
		$this->set("members",$members);
		$this->set('post',false);
		$role_name = $this->request->session()->read("User.role_name");
		if($this->request->is("post")) {
			$this->set('post',true);
			if($role_name == 'member'){
				$member_id = $this->request->session()->read("User.id");
			}else{
				$member_id = $this->request->data["member_id"];	
			}
			$curr_month = $this->request->data["curr_month"];
			$month_year = explode(' ',$curr_month);
			$month = date("m", strtotime($month_year[0]));
			$year = $month_year[1];
			$this->set("month",$month);
			$this->set("year",$year);
			$this->set("member_id",$member_id);
			$this->set("curr_month",$curr_month);
			$conn = ConnectionManager::get('default');
			$query = "select * from gym_daily_workout as gdw 
			LEFT JOIN gym_user_workout as guw on guw.user_workout_id = gdw.id
			where gdw.member_id=$member_id
			AND MONTH(gdw.record_date)=$month
			AND YEAR(gdw.record_date)=$year";
			$data = $conn->execute($query)->fetchAll('assoc');
			if(!empty($data))
				$this->set("data",$data);

			if(isset($this->request->data["export_csv"])) {
				$rows = unserialize($this->request->data["rows"]);
				$filename = "Monthly Workout Report.csv";
				$this->GYMFunction->export_to_csv($filename,$rows);
			}
		}
	}

	public function attendanceReport() {
		// $this->set("view",true);
		if($this->request->is("post")) {
			$att_tbl = TableRegistry::get("gym_attendance");	
			$cls_tbl = TableRegistry::get("class_schedule");
			
			//$sdate = date('Y-m-d',strtotime($this->request->data['sdate']));
			//$edate = date('Y-m-d',strtotime($this->request->data['edate']));
			$sdate = $this->GYMFunction->get_db_format_date($this->request->data['sdate']);
			$edate = $this->GYMFunction->get_db_format_date($this->request->data['edate']);
			$conn = ConnectionManager::get('default');	
			$report_2 = "SELECT  at.class_id,cl.class_name, 
				SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
				SUM(case when `status` ='Absent' then 1 else 0 end) as Absent 
				from `gym_attendance` as at,`class_schedule` as cl where `attendance_date` BETWEEN '{$sdate}' AND '{$edate}' AND at.class_id = cl.id AND at.role_name = 'member' GROUP BY at.class_id";
			
			$report_2 = $conn->execute($report_2);
			$report_2 = $report_2->fetchAll('assoc');			
			$report_2 = $report_2;
			$chart_array[] = array(__('Class'),__('Present'),__('Absent'));
			if(!empty($report_2)) {
				foreach($report_2 as $result) {			
					$cls = $result['class_name'];					
					$chart_array[] = [$result['class_name'],(int)$result["Present"],(int)$result["Absent"]];
				}
			}
			/* new added */
			$att_tbl = TableRegistry::get('gym_attendance');
			$att = $att_tbl->find()->hydrate(false)->toArray();			
			$this->set('attendance',$att);
			/** end new added code */

			$this->set("report_2",$report_2); 
			$this->set("chart_array",$chart_array); 
			$this->set("sdate",$sdate);
			$this->set("edate",$edate);
			$this->set("view",true);
		}
    }

	public function membershipStatusReport() {
		$mem_tbl = TableRegistry::get("GymMember");
		$chart_array = array();
		$chart_array[] = array('Membership','Number Of Member');
		$data = $mem_tbl->find("all")->where(["role_name"=>"member","OR"=>[["membership_status"=>"Expired"],["membership_status"=>"Continue"],["membership_status"=>"Dropped"]]]);
		$data = $data->select(["membership_status","count"=>$data->func()->count('membership_status')])->group("membership_status")->hydrate(false)->toArray();
		if(!empty($data)) {
			foreach($data as $row) {
				$chart_array[]=array( __($row['membership_status']),$row['count']);
			}
		}		
		$this->set("data",$data); 
		$this->set("chart_array",$chart_array); 
	}	

	public function paymentReport() {			
		$conn = ConnectionManager::get('default');
		$table_name = TableRegistry::get("membership_payment");
		$month =array('1'=>"January",'2'=>"February",'3'=>"March",'4'=>"April",
		'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",
		'9'=>"September",'10'=>"Octomber",'11'=>"November",'12'=>"December",);
		$year = date('Y');
		$q="SELECT EXTRACT(MONTH FROM created_date) as date,sum(paid_amount) as count FROM `membership_payment` WHERE YEAR(created_date) =".$year." group by month(created_date) ORDER BY created_date ASC";
		$result = $conn->execute($q);
		$result = $result->fetchAll('assoc');		
		$chart_array = array();
		$chart_array[] = array("Month",__("Fee Payment"));
		if(!empty($result)) {
			foreach($result as $r) {
				$chart_array[]=array(__($month[$r["date"]]),(int)$r["count"]);
			}
		}
		$this->set("result",$result); 		
		$this->set("chart_array",$chart_array); 		
	}
}