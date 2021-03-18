<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Mailer\Email;

class GymEmailSettingController extends AppController
{
	public function index()
	{
		$email = Email::configTransport("default");
		
		$this->set("email",$email);
		if($this->request->is("post") && isset($this->request->data["save_mail"]))
		{
			$post = $this->request->data;
			var_dump($post);
			$file = ROOT . DS . 'config'. DS . 'app.php';       
						
			$host = $post["host"];
			$port = $post["port"];
			$username = $post["username"];
			$password = $post["password"];		
			
			$content = str_replace(["EMAIL_HOST",0,"EMAIL_ID","EMAIL_PASS"],[$host,$port,$username,$password],$content);
						
			return $this->redirect(["controller"=>"gymEmailSetting"]);
		}
		if($this->request->is("post") && isset($this->request->data["send_test"]))
		{
			$post = $this->request->data;
			$host = $post["host"];
			$port = $post["port"];
			$username = $post["username"];
			$password = $post["password"];			
			$to = $post["to"];
			
			Email::configTransport('gmail', [
								'host' => $host,
								'port' => intval($port),
								'username' => $username,
								'password' => $password,
								'className' => 'Smtp',
								'ssl' => true
							]);
							
			$email = new Email();			
			$email->transport('gmail');
			
			$email->from(["{$username}" => "Cake Gym Management"])
				->to("{$to}")
				->subject( _("Test Email"))
				->send("TEST MAIL CONTENT");				
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