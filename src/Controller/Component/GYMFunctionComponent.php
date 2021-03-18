<?php 
namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\View\Helper\UrlHelper;
use Cake\Datasource\ConnectionManger;
use Cake\Mailer\Email;
use Cake\Database\Type; 
Type::build('date')->setLocaleFormat('yyyy-MM-dd');

Class GYMfunctionComponent extends Component
{	
	public function sanitize_string($str)
	{
		$str = urldecode ($str );
		$str = filter_var($str, FILTER_SANITIZE_STRING);
		$str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
		return $str ;
	}
	var $helpers = array('Url'); //Loading Url Helper
	public function createurl($controller,$action)
	{
		return $this->Url->build(["controller" => $controller,"action" => $action]);		
	}
	
	public function membershipExpiredReminder(){
		$gym_member_tbl = TableRegistry::get('gym_member');
		$member = $gym_member_tbl->find()->where(['role_name'=>'member'])->hydrate(false)->toArray();

		//debug($member);die;
		return $member;
	}

	public function get_class_by_id($id){
		$class_schedule_tbl = TableRegistry::get('class_schedule');
		$class_name = $class_schedule_tbl->find()->select('class_name')->where(['id'=>$id])->first();

		return $class_name['class_name'];
	}

	public function uploadImage($file)
	{
		$new_name = "";
		$img_name = $file["name"];	
		if(!empty($img_name))
		{
			$tmp_name = $file["tmp_name"];					
			$ext = substr(strtolower(strrchr($img_name, '.')), 1); 
			$new_name = time() . "_" . rand(000000, 999999). "." . $ext;		
			move_uploaded_file($tmp_name,WWW_ROOT . "/upload/".$new_name);	

			//debug(WWW_ROOT . "/upload/".$new_name);die;
		}
		return $new_name;
	}
	
	public function getSettings($key)
	{
		$settings = TableRegistry::get("GeneralSetting");
		$row = $settings->find()->all();
		$row = $row->first()->toArray();	
		$value = "";
		switch($key)
		{
			CASE "name":
				$value =  $row[$key];
			break;
			CASE "gym_logo":
				$value = $row[$key];
			break;
			CASE "date_format":
				$value = $row[$key];
			break;
			CASE "country":
				$value = $row[$key];
			break;
			CASE "enable_rtl":
				$value = $row[$key];
			break;
			CASE "weight":
				$value = $row[$key];
			break;
			CASE "height":
				$value = $row[$key];
			break;
			CASE "chest":
				$value = $row[$key];
			break;
			CASE "waist":
				$value = $row[$key];
			break;
			CASE "thing":
				$value = $row[$key];
			break;
			CASE "arms":
				$value = $row[$key];
			break;
			CASE "fat":
				$value = $row[$key];
			break;
			CASE "waist":
				$value = $row[$key];
			break;
			CASE "member_can_view_other";
				$value = $row[$key];
			break;
			CASE "enable_message":
				$value = $row[$key];
			break;
			CASE "paypal_email":
				$value = $row[$key];
			break;
			CASE "currency":
				$value = $row[$key];
			break;
			CASE "enable_sandbox":
				$value = $row[$key];
			break;
			CASE "enable_alert":
				$value = $row[$key];
			break;
			CASE "reminder_message":
				$value = $row[$key];
			break;
			CASE "reminder_days":
				$value = $row[$key];
			break;
			CASE "email":
				$value = $row[$key];
			break;
			CASE "staff_can_view_own_member":
				$value = $row[$key];
			break;
			CASE "calendar_lang":
				$value = $row[$key];
			break;
			CASE "system_installed":
				$value = $row[$key];
			break;
			CASE "left_header":
				$value = $row[$key];
			break;
			CASE "footer":
				$value = $row[$key];
			break;
			CASE "datepicker_lang":
				$value = $row[$key];
			break;
			CASE "sys_language":
				@$value = $row[$key];
			break;
			CASE "system_version":
				@$value = (isset($row[$key]))?$row[$key].".0":"1.0";			
			break;
			CASE "time_zone":
				@$value = $row[$key];			
			break;
			CASE "header_color":
				@$value = $row[$key];
			break;
			CASE "sidemenu_color":
				@$value = $row[$key];
			break;
			CASE "stripe_secret_key":
				@$value = $row[$key];
			break;
			CASE "stripe_publishable_key":
				@$value = $row[$key];
			break;
		}
		return $value;
	}
	
	public function date_format()
	{
		$settings = TableRegistry::get("GeneralSetting");
		$row = $settings->find()->all();
		$row = $row->first()->toArray();	
		$value = $row["date_format"];
		return $value;
	}
	
	public function add_membership_history($data)
	{
		$history_table = TableRegistry::get("membershipHistory");
		$history = $history_table->newEntity();
		$history = $history_table->patchEntity($history,$data);
		$history_table->save($history);
	}
	
	public function generate_chart($type,$mid)
	{		
		$report_type_array = array();
		$measurment_table = TableRegistry::get("GymMeasurement");
		$data = $measurment_table->find()->where(["user_id"=>$mid])->hydrate(false)->toArray();			
		foreach($data as $row)
		{			
			$all_data[$row["result_measurment"]][]=array('result'=>$row["result"],'date'=>$row["result_date"]->format('Y-m-d'));
		}
	
		
		switch($type)
		{
			CASE "Weight":
				$report_type_array[] = array('date','Weight	');
				if(isset($all_data['Weight']) && !empty($all_data['Weight']))
				{
					foreach($all_data['Weight'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Thigh":
				$report_type_array[] = array('date','Thigh	');
				if(isset($all_data['Weight']) && !empty($all_data['Thigh']))
				{
					foreach($all_data['Thigh'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Height":
				$report_type_array[] = array('date','Height	');
				if(isset($all_data['Height']) && !empty($all_data['Height']))
				{
					foreach($all_data['Height'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Chest":
				$report_type_array[] = array('date','Chest	');
				if(isset($all_data['Chest']) && !empty($all_data['Chest']))
				{
					foreach($all_data['Chest'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Waist":
				$report_type_array[] = array('date','Waist	');
				if(isset($all_data['Waist']) && !empty($all_data['Waist']))
				{
					foreach($all_data['Waist'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Arms":
				$report_type_array[] = array('date','Arms	');
				if(isset($all_data['Arms']) && !empty($all_data['Arms']))
				{
					foreach($all_data['Arms'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
			CASE "Fat":
				$report_type_array[] = array('date','Fat	');
				if(isset($all_data['Fat']) && !empty($all_data['Fat']))
				{
					foreach($all_data['Fat'] as $r)
					{
						$report_type_array[]=array($r['date'],(int)$r['result']);				
					
					}
				}
			break;
		}
		return $report_type_array;
	}
	
	public function report_option($report_type)
	{
		$report_title = '';
		$htitle = "";
		$ytitle = "";
		if($report_type == 'Weight')
		{
			$report_title = __('Weight Report');
			$htitle = __('Day');
			$vtitle = $this->getSettings( 'weight' );
		}
		if($report_type == 'Thigh')
		{
			$report_title = __('Thigh Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'thing' );
		}
		if($report_type == 'Height')
		{
			$report_title = __('Height Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'height' );
		}
		if($report_type == 'Chest')
		{
			$report_title = __('Chest Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'chest' );
		}
		if($report_type == 'Waist')
		{
			$report_title = __('Waist Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'waist' );
		}
		if($report_type == 'Arms')
		{
			$report_title = __('Arms Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'arms' );
		}
		if($report_type == 'Fat')
		{
			$report_title = __('Fat Report');
			$htitle = __('Day');
			$vtitle =  $this->getSettings( 'fat' );
		}
		$options = Array(
				'title' => $report_title,
				'titleTextStyle' => Array('color' => '#66707e','fontSize' => 16,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),
		
		
				//'bar'  => Array('groupWidth' => '70%'),
				//'lagend' => Array('position' => 'none'),
				'hAxis' => Array(
						'title' => $htitle,
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 11),
						'maxAlternation' => 2
							
						//'annotations' =>Array('textStyle'=>Array('fontSize'=>5))
				),
				'vAxis' => Array(
						'title' => $vtitle,
						'minValue' => 0,
						'maxValue' => 5,
						'format' => '#',
						'titleTextStyle' => Array('color' => '#66707e','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#66707e','fontSize' => 11)
				),
				'colors' => array('#E14444')
			);
		return $options;				
	}
	
	
	public function save_member_login_details($username,$password,$role,$mid)
	{
		$login_tbl = TableRegistry::get("GymLoginDetails");
		$row = $login_tbl->newEntity();
		$data["username"] = $username;
		$data["password"] = $password;
		$data["role_name"] = $role;
		$data["member_id"] = $mid;
		$data["created_date"] = date("Y-m-d");
		$row = $login_tbl->patchEntity($row,$data);
		if($login_tbl->save($row))
		{
			return true;
		}else
		{ 		
			return false;
		}
	}
	
	public function username_check($username)
	{
		$login_tbl = TableRegistry::get("GymLoginDetails");
		$query = $login_tbl->find("all")->where(["username"=>$username]);
		$count = intval($query->count());
		if($count == 1){return false;}else{return true;}
	}
	
	public function get_membership_amount($mid)
	{ 		
		$mem_tbl = TableRegistry::get("Membership");
		$amt = $mem_tbl->get($mid)->toArray();		
		return $amt["membership_amount"];
	}
	
	public function get_membership_name($mid)
	{ 		
		$mem_tbl = TableRegistry::get("Membership");
		$amt = $mem_tbl->get($mid)->toArray();		
		return $amt["membership_label"];
	}
	
	public function get_membership_paymentstatus($mp_id)
	{
	$membership_payment_tbl = TableRegistry::get('MembershipPayment');	
	$result = $membership_payment_tbl->get($mp_id)->toArray();
	if($result['paid_amount'] >= $result['membership_amount'])
		return __('Fully Paid');		
	elseif($result['paid_amount'] == 0 )
		return __('Not Paid');
	else
		return __('Partially Paid');
	
	/*	
	$mem_table = TableRegistry::get('Membership');	
	$signup_fee = $mem_table->get($result['membership_id'])->toArray();
	$signup_fee = $signup_fee["signup_fee"];
	// var_dump($result);
	if($result['paid_amount'] >= $result['membership_amount'] + $signup_fee)
		return 'Fully Paid';		
	elseif($result['paid_amount'] == 0 )
		return 'Not Paid';
	else
		return 'Partially Paid';
	*/
	}	
	
	public function get_user_name($uid)
	{
		$mem_table = TableRegistry::get("GymMember");
		$name = $mem_table->get($uid)->toArray();
		return $name["first_name"] ." ". $name["last_name"];
	}	

	function get_currency_symbol( $currency = '' )
	{			
		$currency = $this->getSettings("currency");
			switch ( $currency ) {
			case 'AED' :
			$currency_symbol = 'د.إ';
			break;
			case 'AUD' :
			case 'CAD' :
			case 'CLP' :
			case 'COP' :
			case 'HKD' :
			case 'MXN' :
			case 'NZD' :
			case 'SGD' :
			case 'USD' :
			$currency_symbol = '&#36;';
			break;
			case 'BDT':
			$currency_symbol = '&#2547;&nbsp;';
			break;
			case 'BGN' :
			$currency_symbol = '&#1083;&#1074;.';
			break;
			case 'BRL' :
			$currency_symbol = '&#82;&#36;';
			break;
			case 'CHF' :
			$currency_symbol = '&#67;&#72;&#70;';
			break;
			case 'CNY' :
			case 'JPY' :
			case 'RMB' :
			$currency_symbol = '&yen;';
			break;
			case 'CZK' :
			$currency_symbol = '&#75;&#269;';
			break;
			case 'DKK' :
			$currency_symbol = 'kr.';
			break;
			case 'DOP' :
			$currency_symbol = 'RD&#36;';
			break;
			case 'EGP' :
			$currency_symbol = 'EGP';
			break;
			case 'EUR' :
			$currency_symbol = '&euro;';
			break;
			case 'GBP' :
			$currency_symbol = '&pound;';
			break;
			case 'HRK' :
			$currency_symbol = 'Kn';
			break;
			case 'HUF' :
			$currency_symbol = '&#70;&#116;';
			break;
			case 'IDR' :
			$currency_symbol = 'Rp';
			break;
			case 'ILS' :
			$currency_symbol = '&#8362;';
			break;
			case 'INR' :
			$currency_symbol = 'Rs.';
			break;
			case 'ISK' :
			$currency_symbol = 'Kr.';
			break;
			case 'KIP' :
			$currency_symbol = '&#8365;';
			break;
			case 'KRW' :
			$currency_symbol = '&#8361;';
			break;
			case 'MYR' :
			$currency_symbol = '&#82;&#77;';
			break;
			case 'NGN' :
			$currency_symbol = '&#8358;';
			break;
			case 'NOK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'NPR' :
			$currency_symbol = 'Rs.';
			break;
			case 'PHP' :
			$currency_symbol = '&#8369;';
			break;
			case 'PLN' :
			$currency_symbol = '&#122;&#322;';
			break;
			case 'PYG' :
			$currency_symbol = '&#8370;';
			break;
			case 'RON' :
			$currency_symbol = 'lei';
			break;
			case 'RUB' :
			$currency_symbol = '&#1088;&#1091;&#1073;.';
			break;
			case 'SEK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'THB' :
			$currency_symbol = '&#3647;';
			break;
			case 'TRY' :
			$currency_symbol = '&#8378;';
			break;
			case 'TWD' :
			$currency_symbol = '&#78;&#84;&#36;';
			break;
			case 'UAH' :
			$currency_symbol = '&#8372;';
			break;
			case 'VND' :
			$currency_symbol = '&#8363;';
			break;
			case 'ZAR' :
			$currency_symbol = '&#82;';
			break;
			default :
			$currency_symbol = $currency;
			break;
		}
		return $currency_symbol;

	}
		
	public function sendAlertEmail()
	{

		$email = new Email('default');
		$check_alert_on = $this->getSettings("enable_alert");
		$sys_email = $this->getSettings("email");
		$sys_name = $this->getSettings("name");
		$reminder_days = $this->getSettings("reminder_days");		
		$display_name = $this->get_user_name(1);

		$search = ["GYM_MEMBERNAME","GYM_MEMBERSHIP","GYM_STARTDATE","GYM_ENDDATE"];				
			
		$mem_table = TableRegistry::get("GymMember");
		$m_table = TableRegistry::get("Membership");
		$data = $mem_table->find("All")->where(function($exp){
				return $exp
						->gte("membership_valid_to",date("Y-m-d"))
						->eq("role_name","member");										
			})->hydrate(false)->toArray();

		$user_ids = array();
		
		foreach($data as $member)
		{
			
			if($member["alert_sent"] == 0)
			{
				/* $membership = $m_table->get($member["selected_membership"])->toArray(); */
				$membership = $m_table->find()->where(["id"=>$member["selected_membership"]])->hydrate(false)->toArray();
		
				if(!empty($membership))
				{
					
					$membership = $membership[0];
					$member_name = $member["first_name"]." ".$member["last_name"];
					//$replace=[];
					$replace = [$member_name,$membership["membership_label"],$member["membership_valid_from"],$member["membership_valid_to"]];
					$reminder_message = $this->getSettings("reminder_message");
					$reminder_message = str_replace($search,$replace,$reminder_message);
					$expiry_date = $member["membership_valid_to"]->format("Y-m-d");
					$mail_date = date('Y-m-d',(strtotime ( "-{$reminder_days} day" , strtotime ( $expiry_date) ) ));
					$curr_date = date("Y-m-d");
					$str_mail_date = strtotime($mail_date);
					$str_curr_date = strtotime($curr_date);
					$last_date = strtotime($expiry_date);
					

					if($curr_date > $mail_date && $curr_date <= $last_date)
					{	
						
						$to = $member["email"];
						$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						/* $email->from([$sys_email => $sys_name])
						->to($to)
						->subject( _("Membership Reminder Alert!"))
						->send($reminder_message);  */
						@mail($to,_("Membership Reminder Alert!"),$reminder_message,$headers);
						$user_ids[] = $member["id"];
					}
				}
			}
		}			
		if(!empty($user_ids))
		{
			$rows = $mem_table->updateAll(["alert_sent"=>1,"alert_send_date"=>date('Y-m-d')],["id IN"=>$user_ids]);
		}	
	}
	
	public function sendAlertEmailToAdmin(){
		$email = new Email('default');
		$check_alert_on = $this->getSettings("enable_alert");
		$sys_email = $this->getSettings("email");
		$sys_name = $this->getSettings("name");
		$reminder_days = $this->getSettings("reminder_days");		
		$display_name = $this->get_user_name(1);
		$curr_date = date("Y-m-d");

		$mem_table = TableRegistry::get("GymMember");

		$admin_email = $mem_table->find()->where(['role_name'=>'administrator'])->hydrate(false)->toArray();

		$m_table = TableRegistry::get("Membership");
		$data = $mem_table->find("All")->where(function($exp){
				return $exp
						->gte("membership_valid_to",date("Y-m-d"))
						->eq("role_name","member")
						->eq("alert_sent",1)
						->eq("admin_alert",0)
						->eq("alert_send_date",date("Y-m-d"));										
			})->hydrate(false)->toArray();

		$subject="{$sys_name} Expired Membership User List";
						
		$message="Dear $display_name,<br><br>
			Below listed user's membership expired coming soon please contact that user to renew his membership.<br>";
		
		$message.="<table border=1 cellpadding=10 style='font-family:Poppins,sans-serif;border-spacing:0px;'>
					<caption style='font-size: 22px;font-weight: 700;'>Member Details </caption>
					<thead>
						<th>No</th>
						<th>Name</th>
						<th>Email</th>
						<th>Membership</th>
						<th>Membership Start Date</th>
						<th>Membership End Date</th>
					</thead>
					<tbody>";
								
		
		$headers="";
		$headers .= "From:  \r\n";
		$headers .= "Reply-To: \r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$i = 1;		

		foreach ($data as $key => $values) {
			$message.="<tr>
					  <td align='center'>".$i."</td>
					  <td align='center'>".$values['first_name']." ".$values['last_name']."</td>
					  <td align='center'>".$values['email']."</td>
					  <td align='center'>".$this->get_membership_name($values['selected_membership'])."</td>
					  <td align='center'>".$values['membership_valid_from']."</td>
					  <td align='center'>".$values['membership_valid_to']."</td>
					</tr>";
			$i++;
			$user_ids[] = $values["id"];
		}
		$message.="</tbody>
						</table>
					<br><br>";

		$regards = 'Regards From,<br>
					GYM Master Team.';

		$message .=" ".$regards;

		if(!empty($user_ids))
		{
			$to = $admin_email[0]['email'];
			$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
			/*$email->from([$sys_email => $sys_name])
					->emailFormat('html')
					->to($to)
					->subject( _("Membership Reminder Alert!"))
					->send($message); */
			@mail($to,_("Membership Reminder Alert!"),$message,$headers);
			$rows = $mem_table->updateAll(["admin_alert"=>1],["id IN"=>$user_ids]);
		}
	}

	public function get_class_by_member($mid)
	{
		$class_table = TableRegistry::get("GymMemberClass");
		$class_sche_table = TableRegistry::get("ClassSchedule");
		$row = $class_table->find()->where(["member_id"=>$mid])->select(["assign_class"])->hydrate(false)->toArray();
		$class = array();
		foreach($row  as $data)
		{
			$class[]= $data["assign_class"];
		}
		return $class;
	}
	
	
	public function index()
	{
		$msg = "First line of text\nSecond line of text";
		$to = "priyal@dasinfomedia.com";
		mail($to,"My subject",$msg);
		$this->autoRender = false ;
	}
	
	public function word_list_for_translation()
	{
		$months = array( __("January"),__("February"),__("March"),__("April"),
		__("May"),__("June"),__("July"),__("August"),__("September"),__("October"),__("November"),__("December"),
		__("You are not authorized to access that location."));
	}
	
	public function check_valid_extension($filename)
	{
		$flag = 2;

		if($filename != '')
		{
			$flag = 0;
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$valid_extension = ['gif','png','jpg','jpeg',"",'JPG','GIF','PNG','JPEG'];
			if(in_array($ext,$valid_extension) )
			{
				$flag = 1;
			}
		}
		return $flag;
	}
	
	function dateformat_PHP_to_jQueryUI($php_format)
	{
		$SYMBOLS_MATCHING = array(
			// Day
			'd' => 'dd',
			'D' => 'D',
			'j' => 'd',
			'l' => 'DD',
			'N' => '',
			'S' => '',
			'w' => '',
			'z' => 'o',
			// Week
			'W' => '',
			// Month
			'F' => 'MM',
			'm' => 'mm',
			'M' => 'M',
			'n' => 'm',
			't' => '',
			// Year
			'L' => '',
			'o' => '',
			'Y' => 'yy',
			'y' => 'y',
			// Time
			'a' => '',
			'A' => '',
			'B' => '',
			'g' => '',
			'G' => '',
			'h' => '',
			'H' => '',
			'i' => '',
			's' => '',
			'u' => ''
		);
		$jqueryui_format = "";
		$escaping = false;
		for($i = 0; $i < strlen($php_format); $i++)
		{
			$char = $php_format[$i];
			if($char === '\\') // PHP date format escaping character
			{
				$i++;
				if($escaping) $jqueryui_format .= $php_format[$i];
				else $jqueryui_format .= '\'' . $php_format[$i];
				$escaping = true;
			}
			else
			{
				if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
				if(isset($SYMBOLS_MATCHING[$char]))
					$jqueryui_format .= $SYMBOLS_MATCHING[$char];
				else
					$jqueryui_format .= $char;
			}
		}
		return $jqueryui_format;
	}
	public function TablesNullFields()
	{
		$conn = ConnectionManager::get('default');
		
		$sql = "ALTER TABLE `activity` CHANGE `cat_id` `cat_id` INT(11) NULL, CHANGE `title` `title` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `assigned_to` `assigned_to` INT(11) NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `category` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `class_schedule` CHANGE `class_name` `class_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `assign_staff_mem` `assign_staff_mem` INT(11) NULL, CHANGE `assistant_staff_member` `assistant_staff_member` INT(11) NULL, CHANGE `location` `location` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `days` `days` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `start_time` `start_time` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `end_time` `end_time` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `class_schedule_list` CHANGE `class_id` `class_id` INT(11) NULL, CHANGE `days` `days` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `start_time` `start_time` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `end_time` `end_time` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `general_setting` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `start_year` `start_year` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `address` `address` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `office_number` `office_number` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `country` `country` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `date_format` `date_format` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `calendar_lang` `calendar_lang` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `gym_logo` `gym_logo` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `cover_image` `cover_image` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `weight` `weight` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `height` `height` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `chest` `chest` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `waist` `waist` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `thing` `thing` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `arms` `arms` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `fat` `fat` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `member_can_view_other` `member_can_view_other` INT(11) NULL, CHANGE `staff_can_view_own_member` `staff_can_view_own_member` INT(11) NULL, CHANGE `enable_sandbox` `enable_sandbox` INT(11) NULL, CHANGE `paypal_email` `paypal_email` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `currency` `currency` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `enable_alert` `enable_alert` INT(11) NULL, CHANGE `reminder_days` `reminder_days` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `reminder_message` `reminder_message` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `enable_message` `enable_message` INT(11) NULL, CHANGE `left_header` `left_header` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `footer` `footer` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `system_installed` `system_installed` INT(11) NULL, CHANGE `datepicker_lang` `datepicker_lang` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `system_version` `system_version` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_accessright` CHANGE `controller` `controller` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `action` `action` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `menu` `menu` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `menu_icon` `menu_icon` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `menu_title` `menu_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `member` `member` INT(11) NULL, CHANGE `staff_member` `staff_member` INT(11) NULL, CHANGE `accountant` `accountant` INT(11) NULL, CHANGE `page_link` `page_link` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_assign_workout` CHANGE `user_id` `user_id` INT(11) NULL, CHANGE `start_date` `start_date` DATE NULL, CHANGE `end_date` `end_date` DATE NULL, CHANGE `level_id` `level_id` INT(11) NULL, CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `direct_assign` `direct_assign` TINYINT(1) NULL, CHANGE `created_date` `created_date` DATE NULL, CHANGE `created_by` `created_by` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_attendance` CHANGE `user_id` `user_id` INT(11) NULL, CHANGE `class_id` `class_id` INT(11) NULL, CHANGE `attendance_date` `attendance_date` DATE NULL, CHANGE `status` `status` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `attendance_by` `attendance_by` INT(11) NULL, CHANGE `role_name` `role_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_daily_workout` CHANGE `workout_id` `workout_id` INT(11) NULL, CHANGE `member_id` `member_id` INT(11) NULL, CHANGE `record_date` `record_date` DATE NULL, CHANGE `result_measurment` `result_measurment` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `result` `result` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `duration` `duration` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `assigned_by` `assigned_by` INT(11) NULL, CHANGE `due_date` `due_date` DATE NULL, CHANGE `time_of_workout` `time_of_workout` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `status` `status` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `note` `note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_event_place` CHANGE `place` `place` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_by` `created_by` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_group` CHANGE `name` `name` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_income_expense` CHANGE `invoice_type` `invoice_type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `invoice_label` `invoice_label` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `supplier_name` `supplier_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `entry` `entry` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `payment_status` `payment_status` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `total_amount` `total_amount` DOUBLE NULL, CHANGE `receiver_id` `receiver_id` INT(11) NULL, CHANGE `invoice_date` `invoice_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_interest_area` CHANGE `interest` `interest` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_levels` CHANGE `level` `level` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_measurement` CHANGE `result_measurment` `result_measurment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `result` `result` FLOAT NULL DEFAULT NULL, CHANGE `user_id` `user_id` INT(11) NULL, CHANGE `result_date` `result_date` DATE NULL, CHANGE `image` `image` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_member` CHANGE `activated` `activated` INT(11) NULL, CHANGE `role_name` `role_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `member_id` `member_id` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `first_name` `first_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `middle_name` `middle_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `last_name` `last_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `member_type` `member_type` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `role` `role` INT(11) NULL, CHANGE `s_specialization` `s_specialization` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `gender` `gender` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `birth_date` `birth_date` DATE NULL, CHANGE `assign_class` `assign_class` INT(11) NULL, CHANGE `assign_group` `assign_group` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `address` `address` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `city` `city` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `state` `state` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `zipcode` `zipcode` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `mobile` `mobile` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `phone` `phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `weight` `weight` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `height` `height` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `chest` `chest` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `waist` `waist` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `thing` `thing` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `arms` `arms` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `fat` `fat` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `username` `username` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `image` `image` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `assign_staff_mem` `assign_staff_mem` INT(11) NULL, CHANGE `intrested_area` `intrested_area` INT(11) NULL, CHANGE `g_source` `g_source` INT(11) NULL, CHANGE `referrer_by` `referrer_by` INT(11) NULL, CHANGE `inquiry_date` `inquiry_date` DATE NULL, CHANGE `trial_end_date` `trial_end_date` DATE NULL, CHANGE `selected_membership` `selected_membership` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `membership_status` `membership_status` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `membership_valid_from` `membership_valid_from` DATE NULL, CHANGE `membership_valid_to` `membership_valid_to` DATE NULL, CHANGE `first_pay_date` `first_pay_date` DATE NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL, CHANGE `alert_sent` `alert_sent` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_member_class` CHANGE `member_id` `member_id` INT(11) NULL, CHANGE `assign_class` `assign_class` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_message` CHANGE `sender` `sender` INT(11) NULL, CHANGE `receiver` `receiver` INT(11) NULL, CHANGE `date` `date` DATETIME NULL, CHANGE `subject` `subject` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `message_body` `message_body` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `status` `status` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_newsletter` CHANGE `api_key` `api_key` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_notice` CHANGE `notice_title` `notice_title` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `notice_for` `notice_for` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `class_id` `class_id` INT(11) NULL, CHANGE `start_date` `start_date` DATE NULL, CHANGE `end_date` `end_date` DATE NULL, CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_by` `created_by` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_nutrition` CHANGE `user_id` `user_id` INT(11) NULL, CHANGE `day` `day` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `breakfast` `breakfast` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `midmorning_snack` `midmorning_snack` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `lunch` `lunch` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `afternoon_snack` `afternoon_snack` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `dinner` `dinner` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `afterdinner_snack` `afterdinner_snack` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `start_date` `start_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `expire_date` `expire_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_nutrition_data` CHANGE `day_name` `day_name` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `nutrition_time` `nutrition_time` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `nutrition_value` `nutrition_value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `nutrition_id` `nutrition_id` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL, CHANGE `create_by` `create_by` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_product` CHANGE `product_name` `product_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `price` `price` DOUBLE NULL, CHANGE `quantity` `quantity` INT(11) NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_reservation` CHANGE `event_name` `event_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `event_date` `event_date` DATE NULL, CHANGE `start_time` `start_time` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `end_time` `end_time` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `place_id` `place_id` INT(11) NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_roles` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_source` CHANGE `source_name` `source_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_store` CHANGE `member_id` `member_id` INT(11) NULL, CHANGE `sell_date` `sell_date` DATE NULL, CHANGE `product_id` `product_id` INT(11) NULL, CHANGE `price` `price` DOUBLE NULL, CHANGE `quantity` `quantity` INT(11) NULL, CHANGE `sell_by` `sell_by` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_user_workout` CHANGE `user_workout_id` `user_workout_id` INT(11) NULL, CHANGE `workout_name` `workout_name` INT(11) NULL, CHANGE `sets` `sets` INT(11) NULL, CHANGE `reps` `reps` INT(11) NULL, CHANGE `kg` `kg` FLOAT NULL, CHANGE `rest_time` `rest_time` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `gym_workout_data` CHANGE `day_name` `day_name` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `workout_name` `workout_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `sets` `sets` INT(11) NULL, CHANGE `reps` `reps` INT(11) NULL, CHANGE `kg` `kg` FLOAT NULL, CHANGE `time` `time` INT(11) NULL, CHANGE `workout_id` `workout_id` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL, CHANGE `created_by` `created_by` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `installment_plan` CHANGE `number` `number` INT(11) NULL, CHANGE `duration` `duration` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `membership` CHANGE `membership_label` `membership_label` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `membership_cat_id` `membership_cat_id` INT(11) NULL, CHANGE `membership_length` `membership_length` INT(11) NULL, CHANGE `membership_class_limit` `membership_class_limit` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `limit_days` `limit_days` INT(11) NULL, CHANGE `limitation` `limitation` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `install_plan_id` `install_plan_id` INT(11) NULL, CHANGE `membership_amount` `membership_amount` DOUBLE NULL, CHANGE `membership_class` `membership_class` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `installment_amount` `installment_amount` DOUBLE NULL, CHANGE `signup_fee` `signup_fee` DOUBLE NULL, CHANGE `gmgt_membershipimage` `gmgt_membershipimage` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_date` `created_date` DATE NULL, CHANGE `created_by_id` `created_by_id` INT(11) NULL, CHANGE `membership_description` `membership_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `membership_activity` CHANGE `activity_id` `activity_id` INT(11) NULL, CHANGE `membership_id` `membership_id` INT(11) NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `membership_history` CHANGE `member_id` `member_id` INT(11) NULL, CHANGE `selected_membership` `selected_membership` INT(11) NULL, CHANGE `assign_staff_mem` `assign_staff_mem` INT(11) NULL, CHANGE `intrested_area` `intrested_area` INT(11) NULL, CHANGE `g_source` `g_source` INT(11) NULL, CHANGE `referrer_by` `referrer_by` INT(11) NULL, CHANGE `inquiry_date` `inquiry_date` DATE NULL, CHANGE `trial_end_date` `trial_end_date` DATE NULL, CHANGE `membership_valid_from` `membership_valid_from` DATE NULL, CHANGE `membership_valid_to` `membership_valid_to` DATE NULL, CHANGE `first_pay_date` `first_pay_date` DATE NULL, CHANGE `created_date` `created_date` DATE NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `membership_payment` CHANGE `member_id` `member_id` INT(11) NULL, CHANGE `membership_id` `membership_id` INT(11) NULL, CHANGE `membership_amount` `membership_amount` DOUBLE NULL, CHANGE `paid_amount` `paid_amount` DOUBLE NULL, CHANGE `start_date` `start_date` DATE NULL, CHANGE `end_date` `end_date` DATE NULL, CHANGE `membership_status` `membership_status` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `payment_status` `payment_status` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `created_date` `created_date` DATE NULL, CHANGE `created_by` `created_by` INT(11) NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `membership_payment_history` CHANGE `mp_id` `mp_id` INT(11) NULL, CHANGE `amount` `amount` INT(11) NULL, CHANGE `payment_method` `payment_method` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `paid_by_date` `paid_by_date` DATE NULL, CHANGE `created_by` `created_by` INT(11) NULL, CHANGE `trasaction_id` `trasaction_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
		$conn->execute($sql);
		
		$sql = "ALTER TABLE `specialization` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
		$conn->execute($sql);
	}
	
	public function get_category_name($id)
	{
		$category_table = TableRegistry::get("Category");
		$name = $category_table->find()->where(["id"=>$id])->select(['name'])->hydrate(false);
		$name = $name->toArray();
		if(!empty($name))
		{
			return $name[0]['name'];
		}	
	}
	
	public function export_to_csv($filename="export.csv",$rows = array())
	{
		
		if(empty($rows))
		{
			return false;
		}
		ob_start();
		$fp = fopen(TMP .$filename, 'w');
		foreach ($rows as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
		ob_clean();
		$file= TMP .$filename;//file location
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
			//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);	
		exit;
	}
	public function getMemberFieldValue($member_id,$field)
	{
		$gymMember = TableRegistry::get("gymMember");
		$gymMemberData = $gymMember->find()->select([$field])->where(['id'=>$member_id])->hydrate(false)->toArray();
		return $gymMemberData[0][$field];
	}
	public function getGeneralSettingFieldValue($field)
	{
		$GeneralSetting = TableRegistry::get("GeneralSetting");
		$GeneralSettingData = $GeneralSetting->find()->select([$field])->hydrate(false)->toArray();
		return $GeneralSettingData[0][$field];
	}
	public function sendworkout($id=null)
	{
				 $todayDate = date('Y-m-d');
		// $todayDate = '2018-11-15';
		
		$GymDailyWorkout = TableRegistry::get("GymDailyWorkout");
		$DailyWorkoutData = $GymDailyWorkout->find()->select(['id','member_id','record_date'])->where(['record_date'=>$todayDate,'reminder_status'=>0,'member_id'=>$id])->hydrate(false)->toArray();
		
		$GYMName = $this->getGeneralSettingFieldValue('name');
		$GYMEmail = $this->getGeneralSettingFieldValue('email');
		$sys_email = $this->getSettings("email");
		$sys_name = $this->getSettings("name");
		
		foreach($DailyWorkoutData as $dailydata)
		{
/* 			debug($dailydata);
			die; */
			// $memberEmail = 'ajay@dasinfomedia.com';
			$memberEmail = $this->getMemberFieldValue($dailydata['member_id'],'email');
			$memberName = $this->getMemberFieldValue($dailydata['member_id'],'first_name');
			
			$mailSubject = "Workout Reminder!";
			$mailMessage = "Dear $memberName
							Today Your Workout Complete.
							Regards,<br>
							$GYMName";
							
			$header = "From:{$GYMEmail} \r\n";
			// $header .= "Cc:afgh@somedomain.com \r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html\r\n";
			
			if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '192.168.1.109')
			{
				$email = new Email('default');
				$email->from([$sys_email => $sys_name])
						->to($memberEmail)
					->subject($mailSubject)
					->send("Dear $memberName
							Today Your Workout Complete.
							Regards,
							$GYMName");
			}
			else
			{			
				$retval = mail ($memberEmail,$mailSubject,$mailMessage,$header);
			}
			
			$result = $GymDailyWorkout->query()
					  ->update()
					  ->set(['reminder_status'=>1])
					  ->where(['record_date'=>$todayDate])
					  ->execute();
									
		} 
		return "123";
	}	
	public function sendWorkoutAlertEmail($id=null)
	{				
		/*  $todayDate = date('Y-m-d');
		// $todayDate = '2018-11-15';
		//return $todayDate; die;
		$GymDailyWorkout = TableRegistry::get("GymDailyWorkout");
		$DailyWorkoutData = $GymDailyWorkout->find()->select(['id','member_id','record_date'])->where(['record_date'=>$todayDate,'reminder_status'=>0])->hydrate(false)->toArray();
		
		$GYMName = $this->getGeneralSettingFieldValue('name');
		$GYMEmail = $this->getGeneralSettingFieldValue('email');
		$sys_email = $this->getSettings("email");
		$sys_name = $this->getSettings("name");
		
		foreach($DailyWorkoutData as $dailydata)
		{
			// $memberEmail = 'ajay@dasinfomedia.com';
			$memberEmail = $this->getMemberFieldValue($dailydata['member_id'],'email');
			$memberName = $this->getMemberFieldValue($dailydata['member_id'],'first_name');
			
			$mailSubject = "Workout Reminder!";
			$mailMessage = "Dear $memberName<br><br>
							Today Your workout assign.<br><br>
							Regards,<br>
							$GYMName";
							
			$header = "From:{$GYMEmail} \r\n";
			// $header .= "Cc:afgh@somedomain.com \r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html\r\n";
			
			if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '192.168.1.109')
			{
				$email = new Email('default');
				$email->from([$sys_email => $sys_name])
						->to($to)
					->subject($mailSubject)
					->send("Dear $memberName
							Today Your workout assign.
							Regards,
							$GYMName");
			}
			else
			{			
				$retval = mail ($memberEmail,$mailSubject,$mailMessage,$header);
			}
			
			$result = $GymDailyWorkout->query()
					  ->update()
					  ->set(['reminder_status'=>1])
					  ->where(['record_date'=>$todayDate])
					  ->execute();
									
		} 
		return "123"; */
	}
	
	public function get_db_format_date($date)
	{
		$date_format = $this->getSettings("date_format");
		$datepicker_lang = $this->getSettings("datepicker_lang");
		
		if($date_format == "F j, Y")
		{
			//echo "hello";
			switch ($datepicker_lang) {
				case 'ar':
					$find = array("يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر");
					break;
					
				case 'zh_CN':
					$find = array("一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月");
					break;
					
				case 'cs':
					$find = array("leden", "únor", "březen", "duben", "květen", "červen" ,"červenec" ,"srpen", "září" ,"říjen", "listopad", "prosinec");
					break;
				
				case 'fr':
					$find = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
					break;
				
				case 'de':
					$find = array("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September","Oktober", "November", "Dezember" );
					break;
					
				case 'el':
					$find = array("Ιανουάριος", "Φεβρουάριος", "Μάρτιος", "Απρίλιος", "Μάιος", "Ιούνιος", "Ιούλιος","Αύγουστος", "Σεπτέμβριος", "Οκτώβριος", "Νοέμβριος", "Δεκέμβριος" );
					break;
					
				case 'it':
					$find = array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto","Settembre", "Ottobre", "Novembre", "Dicembre" );
					break;
					
				case 'ja':
					$find = array("1月", "2月", "3月", "4月", "5月", "6月",	"7月", "8月", "9月", "10月", "11月", "12月" );
					break;
					
				case 'pl':
					$find = array("Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień","Wrzesień", "Październik", "Listopad", "Grudzień" );
					break;
					
				case 'pt_BR':
					$find = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" );
					break;
				
				case 'pt_PT':
					$find = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro","Outubro", "Novembro", "Dezembro");
					break;
					
				case 'fa':
					$find = array("ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژوئیه", "اوت", "سپتامبر", "اکتبر", "نوامبر", "دسامبر" );
					break;
				
				case 'ru':
					$find = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" );
					break;
					
				case 'es':
					$find = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre" );
					break;
					
				case 'th':
					$find = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
					break;
					
				case 'tr':
					$find = array("Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık" );
					break;
					
				case 'ca':
					$find = array("gener", "febrer", "març", "abril", "maig", "juny", "juliol", "agost", "setembre", "octubre", "novembre", "desembre" );
					break;
					
				case 'da':
					$find = array("Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December" );
					break;
				
				case 'et':
					$find = array("Jaanuar", "Veebruar", "Märts", "Aprill", "Mai", "Juuni", "Juuli", "August", "September","Oktoober", "November", "Detsember" );
					break;
					
				case 'fi':
					$find = array("Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu" );
					break;
				
				case 'he':
					$find = array("ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני", "יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר", "דצמבר" );
					break;
					
				case 'hr':
					$find = array("Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac" );
					break;
					
				case 'hu':
					$find = array("Január", "Február", "Március", "Április", "Május", "Június", "Július", "Augusztus", "Szeptember", "Október", "November", "December" );
					break;
					
				case 'id':
					$find = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember" );
					break;
					
				case 'lt':
					$find = array("Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė", "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis", "Lapkritis", "Gruodis" );
					break;
				
				case 'nl':
					$find = array("januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december" );
					break;
				
				case 'no':
					$find = array("januar",	"februar", "mars", "april", "mai", "juni", "juli", "august", "september",		"oktober", "november", "desember" );
					break;
					
				case 'ro':
					$find = array("Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie" );
					break;
					
				case 'sv':
					$find = array("januari", "februari", "mars", "april", "maj", "juni", "juli", "augusti", "september","oktober", "november", "december" );
					break;
					
				case 'vi':
					$find = array("Tháng Một", "Tháng Hai", "Tháng Ba", "Tháng Tư", "Tháng Năm", "Tháng Sáu", "Tháng Bảy", "Tháng Tám", "Tháng Chín", "Tháng Mười", "Tháng Mười Một", "Tháng Mười Hai" );
					break;
					
				default:
					$find = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
					break;
			}
			/* English Month Name */
			$replace = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			/* English Month Name */
			
			/* Replace Month Name to English */
			$save_date = str_replace($find, $replace, $date);
			$save_date = date('Y-m-d',strtotime($save_date));
			
			/* Replace Month Name to English */
		}elseif($date_format == "Y-m-d"){
			$save_date = date("Y-m-d",strtotime($date));
		}elseif($date_format == "m/d/Y"){
			$save_date = date("Y-m-d",strtotime($date));
		}
		return $save_date;
	}
	public function get_db_format_en_lang($date)
	{
		//echo $date;
		$date_format = $this->getSettings("date_format");
		$datepicker_lang = $this->getSettings("datepicker_lang");
		
		if($date_format == "F j, Y")
		{
		
			$find = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			
			switch ($datepicker_lang) {
				case 'ar':
					$replace = array("يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر");
					break;
					
				case 'zh_CN':
					$replace = array("一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月");
					break;
					
				case 'cs':
					$replace = array("leden", "únor", "březen", "duben", "květen", "červen" ,"červenec" ,"srpen", "září" ,"říjen", "listopad", "prosinec");
					break;
				
				case 'fr':
					$replace = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
					break;
				
				case 'de':
					$replace = array("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September","Oktober", "November", "Dezember" );
					break;
					
				case 'el':
					$replace = array("Ιανουάριος", "Φεβρουάριος", "Μάρτιος", "Απρίλιος", "Μάιος", "Ιούνιος", "Ιούλιος","Αύγουστος", "Σεπτέμβριος", "Οκτώβριος", "Νοέμβριος", "Δεκέμβριος" );
					break;
					
				case 'it':
					$replace = array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto","Settembre", "Ottobre", "Novembre", "Dicembre" );
					break;
					
				case 'ja':
					$replace = array("1月", "2月", "3月", "4月", "5月", "6月",	"7月", "8月", "9月", "10月", "11月", "12月" );
					break;
					
				case 'pl':
					$replace = array("Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień","Wrzesień", "Październik", "Listopad", "Grudzień" );
					break;
					
				case 'pt_BR':
					$replace = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" );
					break;
				
				case 'pt_PT':
					$replace = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro","Outubro", "Novembro", "Dezembro");
					break;
					
				case 'fa':
					$replace = array("ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژوئیه", "اوت", "سپتامبر", "اکتبر", "نوامبر", "دسامبر" );
					break;
				
				case 'ru':
					$replace = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" );
					break;
					
				case 'es':
					$replace = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre" );
					break;
					
				case 'th':
					$replace = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
					break;
					
				case 'tr':
					$replace = array("Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık" );
					break;
					
				case 'ca':
					$replace = array("gener", "febrer", "març", "abril", "maig", "juny", "juliol", "agost", "setembre", "octubre", "novembre", "desembre" );
					break;
					
				case 'da':
					$replace = array("Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December" );
					break;
				
				case 'et':
					$replace = array("Jaanuar", "Veebruar", "Märts", "Aprill", "Mai", "Juuni", "Juuli", "August", "September","Oktoober", "November", "Detsember" );
					break;
					
				case 'fi':
					$replace = array("Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu" );
					break;
				
				case 'he':
					$replace = array("ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני", "יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר", "דצמבר" );
					break;
					
				case 'hr':
					$replace = array("Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac" );
					break;
					
				case 'hu':
					$replace = array("Január", "Február", "Március", "Április", "Május", "Június", "Július", "Augusztus", "Szeptember", "Október", "November", "December" );
					break;
					
				case 'id':
					$replace = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember" );
					break;
					
				case 'lt':
					$replace = array("Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė", "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis", "Lapkritis", "Gruodis" );
					break;
				
				case 'nl':
					$replace = array("januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december" );
					break;
				
				case 'no':
					$replace = array("januar",	"februar", "mars", "april", "mai", "juni", "juli", "august", "september",		"oktober", "november", "desember" );
					break;
					
				case 'ro':
					$replace = array("Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie" );
					break;
					
				case 'sv':
					$replace = array("januari", "februari", "mars", "april", "maj", "juni", "juli", "augusti", "september","oktober", "november", "december" );
					break;
					
				case 'vi':
					$replace = array("Tháng Một", "Tháng Hai", "Tháng Ba", "Tháng Tư", "Tháng Năm", "Tháng Sáu", "Tháng Bảy", "Tháng Tám", "Tháng Chín", "Tháng Mười", "Tháng Mười Một", "Tháng Mười Hai" );
					break;
					
				default:
					$replace = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
					break;
			}
		
			/* Replace English to Month Name */
			$save_date = str_replace($find, $replace, $date);
	
			/* Replace English to Month Name */
		}elseif($date_format == "Y-m-d"){
			$save_date = date("Y-m-d",strtotime($date));
		}elseif($date_format == "m/d/Y"){
			$save_date = date("Y-m-d",strtotime($date));
		}
		return $save_date;
	}
	
}