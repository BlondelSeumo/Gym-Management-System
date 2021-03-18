<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class Membership_Activity extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->hasMany("Membership",["foreignKey"=>"membership_id"]);
		$this->hasMany("Activity",["foreignKey"=>"activity_id","dependent"=>true]);		
	}
}
