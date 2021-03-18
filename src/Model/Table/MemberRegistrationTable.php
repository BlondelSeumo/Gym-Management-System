<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class MemberRegistrationTable extends Table{
	
	public function initialize(array $config)
	{	
		$this->BelongsTo("GymMember");		
		$this->BelongsTo("GymMemberClass");		
		$this->BelongsTo("MembershipPayment");		
	}
}