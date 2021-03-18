<?php

	namespace App\Controller;
	use Cake\Event\Event;
	use Cake\I18n\I18n;
	use Cake\I18n\Time;
	use Cake\I18n\Date;
	use Cake\I18n\FrozenDate;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\ConnectionManager;
	use Cake\Routing\Router;
	use Cake\Utility\Security;
	use Cake\Mailer\Email;
	use Cake\Auth\DefaultPasswordHasher;

	class UsersController extends AppController {

		public function initialize() {
			parent::initialize();
		}

		/* 	public function beforeFilter(Event $event)

		{

			// parent::beforeFilter($event);

			// Allow users to register and logout.

			// You should not add the "login" action to allow list. Doing so would

			// cause problems with normal functioning of AuthComponent.

			$this->Auth->allow(['login','index']);

		}

		*/

		public function index() {		
			return $this->redirect(["action"=>"login"]);		
		}

		public function login() {			
			if ($this->request->is('post')) {
				$users = $this->Auth->identify();
				
				if($users) {				
					if($users["role_name"] == "member") {
						$date_passed = false;
						$curr_date = date("Y-m-d");
						if(!empty($users["membership_valid_to"]) || $users["membership_valid_to"] != "") {
							$expiry_date = $users["membership_valid_to"]->format("Y-m-d");					
							if(strtotime($curr_date) > strtotime($expiry_date))
							{
								$date_passed = true;
							}
						}

						if($users["membership_status"] == "Expired" || $date_passed ) {
							$this->Flash->error(__('Sorry, Your account is expired.'));
							return $this->redirect($this->Auth->logout());	
							die;
						}
					}
					$this->Auth->setUser($users);
					$check = $this->request->session()->read("Auth");
					if($check["User"]["activated"] != 1 && $check["User"]["role_name"] == "member") {
						$this->Flash->error(__('Error! Your account not activated yet!'));				
						return $this->redirect($this->Auth->logout());	
					}

					$this->loadComponent("GYMFunction");
					$logo = $this->GYMFunction->getSettings("gym_logo");
					$logo = (!empty($logo)) ? "/webroot/upload/". $logo : "Thumbnail-img.png";
					$name = $this->GYMFunction->getSettings("name");
					$left_header = $this->GYMFunction->getSettings("left_header");
					$footer = $this->GYMFunction->getSettings("footer");
					$is_rtl = ($this->GYMFunction->getSettings("enable_rtl") == 1) ? true : false;
					$datepicker_lang = $this->GYMFunction->getSettings("datepicker_lang");
					$version = $this->GYMFunction->getSettings("system_version");

					$session = $this->request->session();
					$fname = $session->read('Auth.User.first_name');
					$lname = $session->read('Auth.User.last_name');
					$uid = $session->read('Auth.User.id');
					$join_date = $session->read('Auth.User.created_date');
					$profile_img = $session->read('Auth.User.image');
					// $assign_class = $session->read('Auth.User.assign_class');

					$role_name = $session->read('Auth.User.role_name');
					$session->write("User.display_name",$fname." ".$lname);		
					$session->write("User.id",$uid);		
					$session->write("User.role_name",$role_name);		
					$session->write("User.join_date",$join_date);
					$session->write("User.profile_img",$profile_img);
					$session->write("User.logo",$logo);
					$session->write("User.name",$name);
					$session->write("User.left_header",$left_header);				
					$session->write("User.footer",$footer);
					$session->write("User.is_rtl",$is_rtl);
					$session->write("User.dtp_lang",$datepicker_lang);
					$session->write("User.version",$version);

					// $session->write("User.assign_class",$assign_class);
					return $this->redirect($this->Auth->redirectUrl());
				}else{
					$this->Flash->error(__('Invalid username or password, try again'));
				}
			}		
			if($this->Auth->user())
			{
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->viewBuilder()->layout('login');
		}

		public function logout() {	
			$session = $this->request->session();
			$session->delete('User');		
			$session->destroy();		
			return $this->redirect($this->Auth->logout());		
		}	

		public function forgotPassword() {	
			$url = Router::url('/', true);
			if ($this->request->is('post')) {
				$myEmail = $this->request->data('email');
				$myToken = Security::hash(Security::randomBytes(25));
				$user = $this->Users->findByEmail($myEmail)->first();
				if(!empty($user)) {
					$user->password = '';
					$user->is_exist=0;
					$user->token=$myToken;
					if($this->Users->save($user)) {
						$this->Flash->success(__("Reset Passwod link has been sent to your email Please open your inbox"));
						$email = new Email('default');
						$email->from(['gymmaster.cakephp@pushnifty.com' => 'GYM Master']);
						$email->emailFormat('html');
						$email->subject("Reset Password Link");
						$email->to($myEmail);
						$message = "Hello ".$myEmail."<br>Please click link below to reset your password<br><br><a href ='".$url."users/resetPassword/?token=$myToken'>Reset Password</a>";
						$email->send($message);
					}
				}else {
					$this->Flash->error(__('Sorry! Email address is not available here.'));
				}
			}
			$this->viewBuilder()->layout('login');
		}

		public function resetPassword() {
			$token = $_REQUEST['token'];
			$isExist = $this->Users->find()->select('is_exist')->where(['token'=>$token])->first();	
			if($isExist['is_exist'] == '0') {
				if($this->request->is('post')) {
					$hasher = new DefaultPasswordHasher();
					$myPass=$hasher->hash($this->request->data('password'));
					$email = $this->request->data['email'];
					$user = $this->Users->find('all')->where(['token'=>$token])->first();		
					$user->password= $myPass;
					$user->is_exist = 1;
					if($this->Users->save($user)) {
						$this->Flash->success(__('Your Password has been changed successfully.'));
						return $this->redirect(['controller'=>'Users','action'=>'login']);
					}
				}
			}else {
				$this->Flash->error(__('Sorry! The link has been expired Please regenerate Link.'));
				return $this->redirect(["action"=>"forgotPassword"]);		
			}
			$this->viewBuilder()->layout('login');
		}
	}