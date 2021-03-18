<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class MembershipTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->belongsTo("Category");
		$this->belongsTo("Installment_Plan",[
											"foreignKey"=>"install_plan_id",
											"propertyName"=>'duration'
											]);
		$this->belongsTo("Activity");			
		$this->belongsTo("ClassSchedule");			
		$this->hasMany("Membership_Activity",["foreignKey"=>"membership_id"]);
		$this->Membership_Activity->belongsTo("Activity");
		$this->Membership_Activity->belongsTo("Category");
	}
}