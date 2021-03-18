<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Controller\Exception\SecurityException;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Routing\Router;

class InstallerController extends AppController
{
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		if (isset($this->request->params['admin'])) {
            $this->Security->requireSecure();
        }
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['index',"gymTableInstall","success","updateSys"]);
    }

	public function initialize()
    {
        parent::initialize();
		$this->viewBuilder()->layout("gym_install");
		$this->loadComponent('Csrf');
        $this->loadComponent('Security',['blackHoleCallback' => 'forceSSL']);
    }

	public function forceSSL()
    {
        // return $this->redirect('https://' . env('SERVER_NAME') . $this->request->here);
    }


	public function index() {
		/* passthru("nohup mysql -u root -p DBNAME < dump.sql"); */
		if (file_exists(TMP.'installed.txt')) {
			return $this->redirect(["controller"=>"users"]);
			die;
		}else {
			if($this->request->is("post")) {
				$whitelist = [
					// IPv4 address
					'127.0.0.1', 
				
					// IPv6 address
					'::1'
				];
				if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
					$domain_name= $_SERVER['SERVER_NAME'];
					$licence_key =$this->request->data['purchase_key'];
					$email =$this->request->data['purchase_email'];
					$api_server = 'license.dasinfomedia.com';
					$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
					if (!$fp) {
						$server_rerror = 'Down';
					}else {
						$server_rerror = "up";
					}
					if($server_rerror == "up") {
						$url = 'http://licensedasinfomedia.com/index.php';
						$fields = 'result=2&domain='.$domain_name.'&licence_key='.$licence_key.'&email='.$email.'&item_name=gym_master';
						//open connection
						$ch = curl_init();
						
						//set the url, number of POST vars, POST data
						curl_setopt($ch,CURLOPT_URL, $url);
						curl_setopt($ch,CURLOPT_POST,1);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
						
						//execute post
						$chResult = curl_exec($ch);
						curl_close($ch);
						$result = $this->response->body($chResult);
						// $result= $this->response;
						// return $result;
						
						if($result == '1') {
							$this->Flash->error(__('!Please enter correct purchase key'));
							return $this->redirect(['controller'=>'Installer','action' => 'index']);
							die;
						}elseif($result == '2') {
							$this->Flash->error(__('!This purchase key is already registered with the different domain. If you have any issue please contact us at sales@dasinfomedia.com'));	
							return $this->redirect(['controller'=>'Installer','action' => 'index']);
							die;
						}elseif($result == '3') {
							$this->Flash->error(__('!There seems to be some problem please try after sometime or contact us on sales@dasinfomedia.com'));
							return $this->redirect(['controller'=>'Installer','action' => 'index']);
							die;
						}elseif($result == '4') {
							$this->Flash->error(__('!Please enter correct purchase key for this plugin.'));
							return $this->redirect(['controller'=>'Installer','action' => 'index']);
							die;
						}

					}else {
						$this->Flash->error(__('!Connection Problem occurs because server is down.'));
						die;
					}
				}	
				// $this->Flash->error(__('!It is on localhost.'));
				// return $this->redirect(['controller'=>'Installer','action' => 'index']);
				// Key check End
				$file = ROOT . DS . 'config'. DS . 'app.php';
				$content = file_get_contents($file);

				$api_file = WWW_ROOT . 'nghome'. DS . 'connection.php';
				$api_content = file_get_contents($api_file);
				$base_url = Router::url('/', true);
				$upload_url = $base_url . "webroot/upload/";

				$db_host = $this->request->data["db_host"];
				$db_username = $this->request->data["db_username"];
				$db_pass = $this->request->data["db_pass"];
				$db_name = $this->request->data["db_name"];


				$con = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
				if (mysqli_connect_errno())
				{
					echo "Failed to connect to Database : " . mysqli_connect_error();
					die;
				}

				$content = str_replace(["CUST_HOST","CUST_USERNAME","CUST_PW","CUST_DB_NAME"],[$db_host,$db_username,$db_pass,$db_name],$content);
				$status = file_put_contents($file, $content);

				$api_content = str_replace(["CUST_HOST","CUST_USERNAME","CUST_PW","CUST_DB_NAME","CUST_URL","UPLOAD_URL"],[$db_host,$db_username,$db_pass,$db_name,$base_url,$upload_url],$api_content);
				$api_status = file_put_contents($api_file, $api_content);

				$this->gymTableInstall($db_name,$db_username,$db_host,$db_pass);
				$this->insertData($this->request->data);
			}
		}
	}

	private function gymTableInstall($db_name,$db_username,$db_host,$db_pass)
    {
		$this->viewBuilder()->layout("");
		$this->autoRender = false;

		$config = [
					'className' => 'Cake\Database\Connection',
					'driver' => 'Cake\Database\Driver\Mysql',
					'persistent' => false,
					'host' => $db_host,
					'username' => $db_username,
					'password' => $db_pass,
					'database' => $db_name,
					'encoding' => 'utf8',
					'timezone' => 'UTC',
					'flags' => [],
					'cacheMetadata' => true,
					'log' => false,
					'quoteIdentifiers' => false,
					'url' => env('DATABASE_URL', null)
				];

		ConnectionManager::config('install_db', $config);
		$conn = ConnectionManager::get('install_db');

/* 		$sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		$stmt = $conn->execute($sql); */

		$sql="CREATE TABLE IF NOT EXISTS `activity` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `cat_id` int(11) NULL,
			  `title` varchar(100) NULL,
			  `assigned_to` int(11) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
			)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `category` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) NULL,
			  PRIMARY KEY (`id`)
			)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `class_schedule` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `class_name` varchar(100) NULL,
			  `assign_staff_mem` int(11) NULL,
			  `assistant_staff_member` int(11) NULL,
			  `location` varchar(100) NULL,
			  `class_fees` int(11) NULL,
			  `days` varchar(200) NULL,
			  `start_time` varchar(30) NULL,
			  `end_time` varchar(30) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `class_schedule_list` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `class_id` int(11) NULL,
			  `days` varchar(255) NULL,
			  `start_time` varchar(20) NULL,
			  `end_time` varchar(20) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `general_setting` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) NULL,
			  `start_year` varchar(50) NULL,
			  `address` varchar(100) NULL,
			  `office_number` varchar(20) NULL,
			  `country` text NULL,
			  `email` varchar(100) NULL,
			  `date_format` varchar(15) NULL,
			  `calendar_lang` text NULL,
			  `gym_logo` varchar(200) NULL,
			  `cover_image` varchar(200) NULL,
			  `weight` varchar(100) NULL,
			  `height` varchar(100) NULL,
			  `chest` varchar(100) NULL,
			  `waist` varchar(100) NULL,
			  `thing` varchar(100) NULL,
			  `arms` varchar(100) NULL,
			  `fat` varchar(100) NULL,
			  `member_can_view_other` int(11) NULL,
			  `staff_can_view_own_member` int(11) NULL,
			  `enable_sandbox` int(11) NULL,
			  `paypal_email` varchar(50) NULL,
			  `currency` varchar(20) NULL,
			  `enable_alert` int(11) NULL,
			  `reminder_days` varchar(100) NULL,
			  `reminder_message` varchar(255) NULL,
			  `enable_message` int(11) NULL,
			  `left_header` varchar(100) NULL,
			  `footer` varchar(100) NULL,
			  `system_installed` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_accessright` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `controller` text NULL,
			  `action` text NULL,
			  `menu` text NULL,
			  `menu_icon` text NULL,
			  `menu_title` text NULL,
			  `member` int(11) NULL,
			  `staff_member` int(11) NULL,
			  `accountant` int(11) NULL,
			  `page_link` text NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$path = $this->request->base;
		$insert ="INSERT INTO `gym_accessright` (`controller`, `action`, `menu`, `menu_icon`, `menu_title`, `member`, `staff_member`, `accountant`, `page_link`) VALUES
					('StaffMembers', '', 'staff_member', 'staff-member.png', 'Staff Members', 1, 1, 1, '".$path."/staff-members/staff-list'),
					('Membership', '', 'membership', 'membership-type.png', 'Membership Type', 1, 1, 0, '".$path."/membership/membership-list'),
					('GymGroup', '', 'group', 'group.png', 'Group', 1, 1, 0, '".$path."/gym-group/group-list'),
					('GymMember', '', 'member', 'member.png', 'Member', 1, 1, 1, '".$path."/gym-member/member-list'),
					('Activity', '', 'activity', 'activity.png', 'Activity', 1, 1, 0, '".$path."/activity/activity-list'),
					('ClassSchedule', '', 'class-schedule', 'class-schedule.png', 'Class Schedule', 1, 1, 0, '".$path."/class-schedule/class-list'),
					('ClassBooking', '', 'class-booking', 'class-schedule.png', 'Class Booking', 0, 1, 1, '".$path."/class-booking/booking-list'),
					('GymAttendance', '', 'attendance', 'attendance.png', 'Attendance', 0, 1, 0, '".$path."/gym-attendance/attendance'),
					('GymAssignWorkout', '', 'assign-workout', 'assigne-workout.png', 'Assigned Workouts', 1, 1, 0, '".$path."/gym-assign-workout/workout-log'),
					('GymDailyWorkout', '', 'workouts', 'workout.png', 'Workouts', 1, 1, 0, '".$path."/gym-daily-workout/workout-list'),
					('GymAccountant', '', 'accountant', 'accountant.png', 'Accountant', 1, 1, 1, '".$path."/gym-accountant/accountant-list'),
					('MembershipPayment', '', 'membership_payment', 'fee.png', 'Fee Payment', 1, 0, 1, '".$path."/membership-payment/payment-list'),
					('MembershipPayment', '', 'income', 'payment.png', 'Income', 0, 0, 1, '".$path."/membership-payment/income-list'),
					('MembershipPayment', '', 'expense', 'payment.png', 'Expense', 0, 0, 1, '".$path."/membership-payment/expense-list'),
					('GymProduct', '', 'product', 'products.png', 'Product', 0, 1, 1, '".$path."/gym-product/product-list'),
					('GymStore', '', 'store', 'store.png', 'Store', 0, 1, 1, '".$path."/gym-store/sell-record'),
					('GymNewsletter', '', 'news_letter', 'newsletter.png', 'Newsletter', 0, 0, 0, '".$path."/gym-newsletter/setting'),
					('GymMessage', '', 'message', 'message.png', 'Message', 1, 1, 1, '".$path."/gym-message/compose-message'),
					('GymNotice', '', 'notice', 'notice.png', 'Notice', 1, 1, 1, '".$path."/gym-notice/notice-list'),
					('GymNutrition', '', 'nutrition', 'nutrition-schedule.png', 'Nutrition Schedule', 1, 1, 0, '".$path."/gym-nutrition/nutrition-list'),
					('GymReservation', '', 'reservation', 'reservation.png', 'Event', 1, 1, 1, '".$path."/gym-reservation/reservation-list'),
					('GymProfile', '', 'account', 'account.png', 'Account', 1, 1, 1, '".$path."/GymProfile/view_profile'),
					('GymSubscriptionHistory', '', 'subscription_history', 'subscription_history.png', 'Subscription History', 1, 0, 0, '".$path."/GymSubscriptionHistory/')";

		$stmt = $conn->execute($sql);
		$stmt = $conn->execute($insert);

		$sql="CREATE TABLE IF NOT EXISTS `gym_assign_workout` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NULL,
			  `start_date` date NULL,
			  `end_date` date NULL,
			  `level_id` int(11) NULL,
			  `description` text NULL,
			  `direct_assign` tinyint(1) NULL,
			  `created_date` date NULL,
			  `created_by` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_attendance` (
			  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NULL,
			  `class_id` int(11) NULL,
			  `attendance_date` date NULL,
			  `status` varchar(50) NULL,
			  `attendance_by` int(11) NULL,
			  `role_name` varchar(50) NULL,
			  PRIMARY KEY (`attendance_id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_daily_workout` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `workout_id` int(11) NULL,
			  `member_id` int(11) NULL,
			  `record_date` date NULL,
			  `result_measurment` varchar(50) NULL,
			  `result` varchar(100) NULL,
			  `duration` varchar(100) NULL,
			  `assigned_by` int(11) NULL,
			  `due_date` date NULL,
			  `time_of_workout` varchar(50) NULL,
			  `status` varchar(100) NULL,
			  `reminder_status` tinyint(4) DEFAULT 0,
			  `note` text NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `id` (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_event_place` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `place` varchar(100) NULL,
			  `created_by` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_group` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(200) NULL,
			  `image` varchar(255) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_income_expense` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `invoice_type` varchar(100) NULL,
			  `invoice_label` varchar(100) NULL,
			  `supplier_name` varchar(100) NULL,
			  `entry` text NULL,
			  `payment_status` varchar(50) NULL,
			  `total_amount` double NULL,
			  `receiver_id` int(11) NULL,
			  `invoice_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_interest_area` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `interest` varchar(100) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_levels` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `level` varchar(100) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_measurement` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `result_measurment` varchar(100) DEFAULT NULL,
			  `result` float DEFAULT NULL,
			  `user_id` int(11) NULL,
			  `result_date` date NULL,
			  `image` varchar(50) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_member` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `activated` int(11) NULL,
			  `role_name` text NULL,
			  `member_id` text NULL,
			  `is_exist` tinyint(4) NOT NULL DEFAULT '0',
			  `first_name` varchar(100) NULL,
			  `middle_name` varchar(100) NULL,
			  `last_name` varchar(100) NULL,
			  `member_type` text NULL,
			  `role` int(11) NULL,
			  `s_specialization` varchar(255) NULL,
			  `gender` text NULL,
			  `birth_date` date NULL,
			  `assign_class` int(11) NULL,
			  `assign_group` varchar(150) NULL,
			  `address` varchar(100) NULL,
			  `city` varchar(100) NULL,
			  `state` varchar(100) NULL,
			  `zipcode` varchar(100) NULL,
			  `mobile` varchar(20) NULL,
			  `phone` varchar(20) NULL,
			  `email` varchar(100) NULL,
			  `weight` varchar(10) NULL,
			  `height` varchar(10) NULL,
			  `chest` varchar(10) NULL,
			  `waist` varchar(10) NULL,
			  `thing` varchar(10) NULL,
			  `arms` varchar(10) NULL,
			  `fat` varchar(10) NULL,
			  `username` varchar(100) NULL,
			  `password` varchar(255) NULL,
			  `image` varchar(200) NULL,
			  `assign_staff_mem` int(11) NULL,
			  `intrested_area` int(11) NULL,
			  `g_source` int(11) NULL,
			  `referrer_by` int(11) NULL,
			  `inquiry_date` date NULL,
			  `trial_end_date` date NULL,
			  `selected_membership` varchar(100) NULL,
			  `membership_status` text NULL,
			  `membership_valid_from` date NULL,
			  `membership_valid_to` date NULL,
			  `first_pay_date` date NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  `alert_sent` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$username = $this->request->data["lg_username"];
		$password = $this->request->data["confirm"];

		$hasher = new DefaultPasswordHasher();
		$password = $hasher->hash($password);
		$curr_date = date("Y-m-d");

		$insert = "INSERT INTO `gym_member` (`role_name`,`first_name`, `middle_name`, `last_name`,`gender`, `birth_date`,`address`, `city`, `state`, `zipcode`, `mobile`, `phone`, `email`,`username`, `password`, `image`,`created_date`) VALUES
		('administrator','Admin', '', '', 'male', '2016-07-01','null', 'null', 't', '123123', '123123123', '', 'admin@admin.com', '{$username}', '{$password}', 'Thumbnail-img2.png','{$curr_date}')";

		$stmt = $conn->execute($sql);
		$stmt = $conn->execute($insert);

		$pass = $hasher->hash('sergio');;
		$insert = "INSERT INTO `gym_member` (`role_name`, `member_id`, `first_name`, `middle_name`, `last_name`, `member_type`, `role`, `gender`, `birth_date`, `assign_group`, `address`, `city`, `state`, `zipcode`, `mobile`, `phone`, `email`, `weight`, `height`, `chest`, `waist`, `thing`, `arms`, `fat`, `username`, `password`, `image`, `assign_staff_mem`, `intrested_area`, `g_source`, `referrer_by`, `selected_membership`, `membership_status`, `created_by`, `created_date`, `alert_sent`) VALUES
		('staff_member', '', 'Sergio', '', 'Romero', '', 1, 'male', '2016-08-10', '', 'Address line', 'City', '', '', '2288774455', '', 'sergio@sergio.com', '', '', '', '', '', '', '', 'sergio', '{$pass}', 'Thumbnail-img2.png', 0, 0, 0, 0, '', '', 0, '2016-08-22', 0)";

		$stmt = $conn->execute($sql);
		$stmt = $conn->execute($insert);


		$sql="CREATE TABLE IF NOT EXISTS `gym_member_class` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `member_id` int(11) NULL,
			  `assign_class` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_message` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `sender` int(11) NULL,
			  `receiver` int(11) NULL,
			  `date` datetime NULL,
			  `subject` varchar(150) NULL,
			  `message_body` text NULL,
			  `status` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_newsletter` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `api_key` varchar(255) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_notice` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `notice_title` varchar(100) NULL,
			  `notice_for` text NULL,
			  `class_id` int(11) NULL,
			  `start_date` date NULL,
			  `end_date` date NULL,
			  `comment` varchar(200) NULL,
			  `created_by` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_nutrition` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NULL,
			  `day` varchar(50) NULL,
			  `breakfast` text NULL,
			  `midmorning_snack` text NULL,
			  `lunch` text NULL,
			  `afternoon_snack` text NULL,
			  `dinner` text NULL,
			  `afterdinner_snack` text NULL,
			  `start_date` varchar(20) NULL,
			  `expire_date` varchar(20) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_nutrition_data` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `day_name` varchar(30) NULL,
			  `nutrition_time` varchar(30) NULL,
			  `nutrition_value` text NULL,
			  `nutrition_id` int(11) NULL,
			  `created_date` date NULL,
			  `create_by` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_product` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_name` varchar(100) NULL,
			  `price` double NULL,
			  `quantity` int(11) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_reservation` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `event_name` varchar(100) NULL,
			  `event_date` date NULL,
			  `start_time` varchar(20) NULL,
			  `end_time` varchar(20) NULL,
			  `place_id` int(11) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_roles` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_source` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `source_name` varchar(100) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_store` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `member_id` int(11) NULL,
			  `sell_date` date NULL,
			  `product_id` int(11) NULL,
			  `price` double NULL,
			  `quantity` int(11) NULL,
			  `sell_by` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_user_workout` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_workout_id` int(11) NULL,
			  `workout_name` int(11) NULL,
			  `sets` int(11) NULL,
			  `reps` int(11) NULL,
			  `kg` float NULL,
			  `rest_time` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `gym_workout_data` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `day_name` varchar(15) NULL,
			  `workout_name` varchar(100) NULL,
			  `sets` int(11) NULL,
			  `reps` int(11) NULL,
			  `kg` float NULL,
			  `time` int(11) NULL,
			  `workout_id` int(11) NULL,
			  `created_date` date NULL,
			  `created_by` int(11) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `installment_plan` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `number` int(11) NULL,
			  `duration` varchar(50) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `membership` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `membership_label` varchar(100) NULL,
			  `membership_cat_id` int(11) NULL,
			  `membership_length` int(11) NULL,
			  `membership_class_limit` varchar(20) NULL,
			  `limit_days` int(11) NULL,
			  `limitation` varchar(20) NULL,
			  `install_plan_id` int(11) NULL,
			  `membership_amount` double NULL,
			  `membership_class` varchar(255) NULL,
			  `installment_amount` double NULL,
			  `signup_fee` double NULL,
			  `gmgt_membershipimage` varchar(255) NULL,
			  `created_date` date NULL,
			  `created_by_id` int(11) NULL,
			  `membership_description` text NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `membership_activity` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `activity_id` int(11) NULL,
			  `membership_id` int(11) NULL,
			  `created_by` int(11) NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `membership_history` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `member_id` int(11) NULL,
			  `selected_membership` int(11) NULL,
			  `assign_staff_mem` int(11) NULL,
			  `intrested_area` int(11) NULL,
			  `g_source` int(11) NULL,
			  `referrer_by` int(11) NULL,
			  `inquiry_date` date NULL,
			  `trial_end_date` date NULL,
			  `membership_valid_from` date NULL,
			  `membership_valid_to` date NULL,
			  `first_pay_date` date NULL,
			  `created_date` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `membership_payment` (
			  `mp_id` int(11) NOT NULL AUTO_INCREMENT,
			  `member_id` int(11) NULL,
			  `membership_id` int(11) NULL,
			  `membership_amount` double NULL,
			  `paid_amount` double NULL,
			  `start_date` date NULL,
			  `end_date` date NULL,
			  `membership_status` varchar(50) NULL,
			  `payment_status` varchar(20) NULL,
			  `created_date` date NULL,
			  `created_by` int(11) NULL,
			  PRIMARY KEY (`mp_id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `membership_payment_history` (
			  `payment_history_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `mp_id` int(11) NULL,
			  `amount` int(11) NULL,
			  `payment_method` varchar(50) NULL,
			  `paid_by_date` date NULL,
			  `created_by` int(11) NULL,
			  `trasaction_id` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`payment_history_id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `specialization` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		/* new table add*/
		$sql="CREATE TABLE IF NOT EXISTS `class_booking` (
			  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
			  `full_name` varchar(255) NULL,
			  `gender` varchar(10) NULL,
			  `mobile_no` varchar(100) NULL,
			  `email` varchar(50) NULL,
			  `address` varchar(255) NULL,
			  `city` varchar(50) NULL,
			  `state` varchar(50) NULL,
			  `zipcode` int(11) NULL,
			  `class_id` varchar(10) NULL,
			  `booking_date` date NULL,
			  `booking_type` varchar(20) NULL,
			  `booking_amount` varchar(50) NULL,
			  `transaction_id` varchar(100) NULL,
			  `payment_by` varchar(10) NULL,
			  `status` varchar(10) NULL,
			  `created_at` date NULL,
			  PRIMARY KEY (`booking_id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `activity_video` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `activity_id` int(11) NULL,
			  `video` text NULL,
			  `created_at` date NULL,
			  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);

		$sql = "ALTER TABLE `general_setting` ADD `enable_rtl` INT(11) NULL DEFAULT '0'";
			$conn->execute($sql);

		$sql = "ALTER TABLE `general_setting` CHANGE `enable_rtl` `enable_rtl` INT(11) NULL DEFAULT '0'";
			$conn->execute($sql);

		$sql = "ALTER TABLE `general_setting` ADD `datepicker_lang` TEXT NULL DEFAULT NULL";
			$conn->execute($sql);

		$sql = "ALTER TABLE `general_setting` ADD `system_version` TEXT NULL DEFAULT NULL";
			$conn->execute($sql);

		$sql = "ALTER TABLE `general_setting` ADD `sys_language` VARCHAR(20) NOT NULL DEFAULT 'en'";
			$conn->execute($sql);

		$sql = "ALTER TABLE `general_setting`  ADD `header_color` VARCHAR(10) NULL  AFTER `sys_language`,  ADD `sidemenu_color` VARCHAR(10) NULL  AFTER `header_color`";
			$conn->execute($sql);

		$sql = "ALTER TABLE `general_setting` ADD `stripe_secret_key` TEXT NULL AFTER `sidemenu_color`, ADD `stripe_publishable_key` TEXT NULL AFTER `stripe_secret_key`";
		$stmt = $conn->execute($sql);

		$sql = "ALTER TABLE `gym_member` ADD `alert_send_date` DATE NULL AFTER `alert_sent`";
			$conn->execute($sql);

		$sql = "ALTER TABLE `gym_member` ADD `admin_alert` INT NULL DEFAULT '0' AFTER `alert_sent`";
			$conn->execute($sql);

		$sql = "UPDATE `general_setting` SET datepicker_lang = 'en'";
			$conn->execute($sql);

		$path = $this->request->base;
		$sql = "INSERT INTO `gym_accessright` (`controller`, `action`, `menu`, `menu_icon`, `menu_title`, `member`, `staff_member`, `accountant`, `page_link`) VALUES ('Reports', '', 'report', 'report.png', 'Report', '1', '1', '1', '".$path."/reports/membership-report')";
			$conn->execute($sql);

		$sql = "ALTER TABLE `general_setting` ADD `time_zone` VARCHAR(20) NOT NULL DEFAULT 'UTC' AFTER `datepicker_lang`";
			$conn->execute($sql);

		$sql = "ALTER TABLE `gym_member` ADD `token` VARCHAR(300) NULL DEFAULT NULL AFTER `member_id`";
			$conn->execute($sql);

		$sql = "SHOW COLUMNS FROM `membership` LIKE 'membership_class' ";
			$columns = $conn->execute($sql)->fetch();
			if($columns == false)
			{
				$sql = "ALTER TABLE `membership` ADD `membership_class` varchar(255) NULL";
				$conn->execute($sql);
			}
		//file_put_contents(TMP.'installed.txt', date('Y-m-d, H:i:s'));

		$this->redirect(["action"=>"success"]);

	}

	private function insertData($data)
	{
		$this->viewBuilder()->layout("");
		$this->autoRender = false;
		$year = date("Y");
		$conn = ConnectionManager::get('install_db');
		$sql  = "INSERT INTO `general_setting` (`name`, `start_year`, `address`, `office_number`, `country`, `email`, `date_format`, `calendar_lang`, `gym_logo`, `cover_image`, `weight`, `height`, `chest`, `waist`, `thing`, `arms`, `fat`, `member_can_view_other`, `staff_can_view_own_member`, `enable_sandbox`, `paypal_email`, `currency`, `enable_alert`, `reminder_days`, `reminder_message`, `enable_message`, `left_header`, `footer`,`system_installed`,`datepicker_lang`,`sys_language`,`header_color`,`sidemenu_color`,`stripe_secret_key`,`stripe_publishable_key`) VALUES
			('{$data['name']}', '{$year}', 'address', '8899665544', '{$data['country']}','{$data['email']}', '{$data['date_format']}', '{$data['sys_language']}', '', 'cover-image.png', 'KG', 'Centimeter', 'Inches', 'Inches', 'Inches', 'Inches', 'Percentage', 0, 1, 0, 'your_id@paypal.com', '{$data['currency']}', 1, '5', 'Hello GYM_MEMBERNAME,\r\n      Your Membership  GYM_MEMBERSHIP  started at GYM_STARTDATE and it will expire on GYM_ENDDATE.\r\nThank You.', 1,'GYM MASTER','Copyright © 2016-2017. All rights reserved.',1,'{$data['sys_language']}','{$data['sys_language']}','#1DB198','','YOUR SECRET KEY','YOUR PUBLISHABLE KEY')";
		$stmt = $conn->execute($sql);


		$sql = "INSERT INTO `category` (`name`) VALUES
				('Regular'),
				('Limited'),
				('Total Gym Exercises for Abs (Abdomininals)'),
				('Total Gym Exercises for Legs'),
				('Total Gym Exercises for Biceps'),
				('Exercise')";
		$stmt = $conn->execute($sql);

		$sql = "INSERT INTO `activity` (`cat_id`, `title`, `assigned_to`, `created_by`, `created_date`) VALUES
				( 5, 'Hyperextension', 2, 1, '2016-08-22'),
				(3, 'Crunch', 2, 1, '2016-08-22'),
				(4, 'Leg curl', 2, 1, '2016-08-22'),
				(4, 'Reverse Leg Curl', 2, 1, '2016-08-22'),
				(6, 'Body Conditioning', 2, 1, '2016-10-19'),
				(6, 'Free Weights', 2, 1, '2016-10-19'),
				(3, 'Fixed Weights', 2, 1, '2016-10-19'),
				(3, 'Resisted Crunch', 2, 1, '2016-10-19'),
				(6, 'Plank', 2, 1, '2016-10-19'),
				(4, 'High Leg Pull-In', 2, 1, '2016-10-19'),
				(4, 'Low Leg Pull-In', 2, 1, '2016-10-19')";
		$stmt = $conn->execute($sql);

		$sql = "INSERT INTO `installment_plan` (`number`, `duration`) VALUES
				(1, 'Month'),
				(1, 'Week'),
				(1, 'Year')";
		$stmt = $conn->execute($sql);

		$sql = "INSERT INTO `gym_roles` (`name`) VALUES
				('Yoga')";
		$stmt = $conn->execute($sql);

		$sql = "INSERT INTO `class_schedule` (`class_name`, `assign_staff_mem`, `assistant_staff_member`, `location`,`class_fees`, `days`, `start_time`, `end_time`, `created_by`, `created_date`) VALUES
				('Yoga Class', 2, 0, 'At Gym Facility','5', '[\"Sunday\",\"Saturday\"]', '8:00', '10:00' ,1, '2016-08-22'),
				('Aerobics Class', 2, 0, 'Class 1', '5','[\"Sunday\",\"Friday\",\"Saturday\"]', '17:15', '18:15', 1, '2016-08-22'),
				('HIT Class', 2, 2, 'Old location', '5','[\"Sunday\",\"Tuesday\",\"Thursday\"]', '18:30', '19:45' ,1, '2016-08-22'),
				('Cardio Class', 2, 0, 'At Gym Facility', '5', '[\"Friday\",\"Saturday\"]', '15:30', '16:30',1, '2016-08-22'),
				('Pilates', 2, 0, 'Old location', '5', '[\"Sunday\"]', '12:00', '15:15', 1, '2016-08-22'),
				('Zumba Class',2, 0, 'New Location', '5', '[\"Saturday\"]', '20:30', '22:30', 1, '2016-08-22'),
				('Power Yoga Class', 2, 0, 'New Location', '5', '[\"Monday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\"]', '9:15', '11:45', 1, '2016-08-22')";
		$stmt = $conn->execute($sql);

		$platinum_class = json_encode(["1","2","3","4","5","6","7"]);
		$gold_class = json_encode(["1","2","3","4","5"]);
		$silver_class = json_encode(["4","6","7"]);
		$sql = "INSERT INTO `membership` (`membership_label`, `membership_cat_id`, `membership_length`, `membership_class_limit`, `limit_days`, `limitation`, `install_plan_id`, `membership_amount`, `membership_class`, `installment_amount`, `signup_fee`, `gmgt_membershipimage`, `created_date`, `created_by_id`, `membership_description`) VALUES
				('Platinum Membership', 1, 360, 'Unlimited', 0, '', 1, 500, '$platinum_class', 42, 5, '', '2016-08-22', 1, '<p>Platinum membership description<br></p>'),
				('Gold Membership', 1, 300, 'Unlimited', 0, '', 1, 450, '$gold_class', 37, 5, '', '2016-08-22', 1, '<p>Gold membership description<br></p>'),
				('Silver Membership', 2, 180, 'Limited', 0, 'per_week', 2, 200,'$silver_class', 5, 5, '', '2016-08-22', 1, '<p>Silver &nbsp;membership description</p>')";
		$stmt = $conn->execute($sql);

		$sql = "UPDATE `general_setting` SET `header_color`='#1db198',`sidemenu_color`='#000000'";
		$stmt = $conn->execute($sql);
		
		$this->updateSys();
	}


	public function updateSys()
	{
		$this->autoRender = false;

		$conn = file_exists(TMP.'installed.txt') ? ConnectionManager::get('default') : ConnectionManager::get('install_db') ;
	
		$sql = "SELECT * from general_setting";
		$settings = $conn->execute($sql)->fetchAll("assoc");

		if(!empty($settings))
		{
			if(isset($settings[0]["system_version"]))
			{
				$version = $settings[0]["system_version"];
				switch($version)
				{
					CASE "2": /* If old version is 2*/
						/* update queries for version 3 */
					break ;

					CASE "9":
					$sql = "ALTER TABLE `general_setting` ADD `time_zone` VARCHAR(20) NOT NULL DEFAULT 'UTC' AFTER `datepicker_lang`";
					$conn->execute($sql);

					$sql = "ALTER TABLE `gym_member` ADD `token` VARCHAR(300) NULL DEFAULT NULL AFTER `member_id`";
					$conn->execute($sql);
					break ;
					CASE "12":
						/* Nothing to update query */
					break ;

					CASE "13":
						$sql="CREATE TABLE IF NOT EXISTS `class_booking` (
								  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
								  `full_name` varchar(255) NULL,
								  `gender` varchar(10) NULL,
								  `mobile_no` varchar(100) NULL,
								  `email` varchar(50) NULL,
								  `address` varchar(255) NULL,
								  `city` varchar(50) NULL,
								  `state` varchar(50) NULL,
								  `zipcode` int(11) NULL,
								  `class_id` varchar(10) NULL,
								  `booking_date` date NULL,
								  `booking_type` varchar(10) NULL,
								  `booking_amount` varchar(50) NULL,
								  `transaction_id` varchar(100) NULL,
								  `payment_by` varchar(10) NULL,
								  `status` varchar(10) NULL,
								  `created_at` date NULL,
								  PRIMARY KEY (`booking_id`)
									)DEFAULT CHARSET=utf8";

							$stmt = $conn->execute($sql);
						
						$sql="CREATE TABLE IF NOT EXISTS `activity_video` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `activity_id` int(11) NULL,
							  `video` text NULL,
							  `created_at` date NULL,
							  PRIMARY KEY (`id`)
								)DEFAULT CHARSET=utf8";

						$stmt = $conn->execute($sql);

						$sql = "ALTER TABLE `class_schedule`  ADD `class_fees` int(11) NULL  AFTER `location`";
						$stmt = $conn->execute($sql);

						$sql = "ALTER TABLE `general_setting`  ADD `header_color` VARCHAR(10) NULL  AFTER `sys_language`,  ADD `sidemenu_color` VARCHAR(10) NULL  AFTER `header_color`";
						$stmt = $conn->execute($sql);

						$insert ="UPDATE `general_setting` SET `header_color`='#1db198',`sidemenu_color`='#000000' WHERE id=1";

						$stmt = $conn->execute($insert);

						$sql = "ALTER TABLE `general_setting` ADD `stripe_secret_key` TEXT NULL AFTER `sidemenu_color`, ADD `stripe_publishable_key` TEXT NULL AFTER `stripe_secret_key`";
						$stmt = $conn->execute($sql);

						$sql = "ALTER TABLE `gym_member` ADD `alert_send_date` DATE NULL AFTER `alert_sent`";
						$stmt = $conn->execute($sql);

						$sql = "ALTER TABLE `gym_member` ADD `admin_alert` INT NULL DEFAULT '0' AFTER `alert_sent`";
						$stmt = $conn->execute($sql);

						$path = $this->request->base;
						$insert ="INSERT INTO `gym_accessright` (`controller`, `action`, `menu`, `menu_icon`, `menu_title`, `member`, `staff_member`, `accountant`, `page_link`) VALUES ('ClassBooking', '', 'class-booking', 'class-schedule.png', 'Class Booking', 0, 1, 0, '".$path."/class-booking/booking-list')";

						$stmt = $conn->execute($insert);

						$sql = "UPDATE `general_setting` SET stripe_secret_key = 'YOUR SECRET KEY', `stripe_publishable_key`= 'YOUR PUBLISHABLE KEY' ";
						$conn->execute($sql);

						$sql = "UPDATE `general_setting` SET system_version = '17'";
						$conn->execute($sql);
					break;

					$this->Flash->success(__("Success! System Update Successfully."));
				}

			}
			else
			{
				/* 1st Update */

				/*-------- 06-03-2019 --------- */
				/* $sql = "ALTER TABLE `general_setting` ADD `enable_rtl` INT(11) NULL DEFAULT '0'";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` CHANGE `enable_rtl` `enable_rtl` INT(11) NULL DEFAULT '0'";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` ADD `datepicker_lang` TEXT NULL DEFAULT NULL";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` ADD `system_version` TEXT NULL DEFAULT NULL";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` ADD `sys_language` VARCHAR(20) NOT NULL DEFAULT 'en'";
				$conn->execute($sql); */
				// $sql = "UPDATE `general_setting` SET system_version = '2'";
				// $sql = "UPDATE `general_setting` SET system_version = '12'";
				$sql = "UPDATE `general_setting` SET system_version = '17'";
				$conn->execute($sql);

				/* $sql = "UPDATE `general_setting` SET datepicker_lang = 'en'";
				$conn->execute($sql);

				$path = $this->request->base;
				$sql = "INSERT INTO `gym_accessright` (`controller`, `action`, `menu`, `menu_icon`, `menu_title`, `member`, `staff_member`, `accountant`, `page_link`) VALUES ('Reports', '', 'report', 'report.png', 'Report', '1', '1', '1', '".$path."/reports/membership-report')";
				$conn->execute($sql);

				$sql = "ALTER TABLE `general_setting` ADD `time_zone` VARCHAR(20) NOT NULL DEFAULT 'UTC' AFTER `datepicker_lang`";
				$conn->execute($sql); */

				// $sql = "ALTER TABLE `gym_daily_workout` ADD `reminder_status` TINYINT NOT NULL DEFAULT '0' AFTER `status`";
				// $conn->execute($sql);

				/* $sql = "ALTER TABLE `gym_member` ADD `token` VARCHAR(300) NULL DEFAULT NULL AFTER `member_id`";
				$conn->execute($sql);

				$sql = "SHOW COLUMNS FROM `membership` LIKE 'membership_class' ";
				$columns = $conn->execute($sql)->fetch();
				if($columns == false)
				{
					$sql = "ALTER TABLE `membership` ADD `membership_class` varchar(255) NULL";
					$conn->execute($sql);
				}	 */
				/*-------- 06-03-2019 --------- */
			}
		}
		file_put_contents(TMP.'installed.txt', date('Y-m-d, H:i:s'));
		return $this->redirect(["controller"=>"users","action"=>"login"]);
	}


	public function success()
	{

	}

	public function isAuthorized($user)
	{
		return true;
	}
}
