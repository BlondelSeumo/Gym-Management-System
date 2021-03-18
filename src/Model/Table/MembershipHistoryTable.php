<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class MembershipHistoryTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
		$this->belongsTo("Membership",["foreignKey"=>"selected_membership"]);
	}
}