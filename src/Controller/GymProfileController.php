<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;

class GymProfileController extends AppController
{
	public function viewProfile()
	{
		$session = $this->request->session()->read("User");
		$user_data = $this->GymProfile->GymMember->get($session["id"]);
		$cover_image = $this->GymProfile->GeneralSetting->find()->select('cover_image')->hydrate(false)->toArray();
		$coverIMG = $cover_image[0]['cover_image'];
		$this->set("data",$user_data->toArray());
		$this->set("cover_image",$coverIMG);
		
		if($this->request->is("post") )
		{
			if(isset($this->request->data["save_change"]))
			{			
				$post = $this->request->data;
				$saved_pass = $this->GymProfile->GymMember->get($this->Auth->user('id'))->password;
				$curr_pass = (new DefaultPasswordHasher)->check($post["current_password"],$saved_pass);
				if($curr_pass)
				{
					if($post["password"] != $post["confirm_password"])
					{
						$this->Flash->error(__("Error! New password and confirm password does not matched.Please try again."));
					}else{
						
						$update["password"] = $this->request->data["confirm_password"];
						$update_row = $this->GymProfile->GymMember->patchEntity($user_data,$update);
						if($this->GymProfile->GymMember->save($update_row))
						{
							$this->Flash->success(__("Success! Password Updated Successfully"));
						}
					}
				}else{
					$this->Flash->error(__("Error! Current password is wrong.Please try again."));
				}
				
			}
			if(isset($this->request->data["profile_save_change"]))
			{
				$post = $this->request->data;
				
				$curr_email = $this->Auth->User('email');
				if($curr_email != $post["email"])
				{
					$emails = $this->GymProfile->GymMember->find("all")->where(["email"=>$post["email"]]);
					$count = $emails->count();
				}else{
					$count = 0 ;
				}
				if($count == 0)
				{
					
					$post['birth_date']=date('Y-m-d',strtotime($post['birth_date']));
					$update_row = $this->GymProfile->GymMember->patchEntity($user_data,$post);
					
					if($this->GymProfile->GymMember->save($update_row))
					{
						$this->Flash->success(__("Success! Information Updated Successfully"));
						return $this->redirect(["action"=>"viewProfile"]);
					}
				}else{
					$this->Flash->error(__("Error! Not Update.Please try again."));
				}
			}			
		}
	}
}