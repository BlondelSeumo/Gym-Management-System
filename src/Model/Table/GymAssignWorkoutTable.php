<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class GymAssignWorkoutTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("GymMember",["foreignKey"=>"user_id"]);
		$this->belongsTo("GymLevels");
		$this->belongsTo("Activity");
		$this->belongsTo("Category");
		$this->hasMany("GymWorkoutData");		
	}
}