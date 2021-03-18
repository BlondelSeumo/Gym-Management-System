<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ActivityTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");		
		$this->hasMany("MembershipActivity",["foreignKey"=>"activity_id","dependent"=>true]);		
		$this->hasMany("Membership");
		$this->belongsTo("Category",["foreignKey"=>"cat_id"]);
		// $this->belongsTo("StaffMembers",["foreignKey"=>"assigned_to"]);
		$this->belongsTo("GymMember",["foreignKey"=>"assigned_to"]);
	}
}