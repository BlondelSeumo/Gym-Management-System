<?php
namespace App\Model\Table;
use Cake\ORM\Table;

Class GymMemberClassTable extends Table{
	
	public function initialize(array $config)
	{		
		$this->addBehavior('Timestamp');
		$this->belongsTo("GymMember",["foreignKey"=>"member_id"]);		
	}
}