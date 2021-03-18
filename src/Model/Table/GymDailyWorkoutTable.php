<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

class GymDailyWorkoutTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("Activity");
		$this->belongsTo("GymMeasurement");
		$this->belongsTo("GymMember");
		$this->belongsTo("GymUserWorkout");
		$this->belongsTo("GymAssignWorkout");
		$this->belongsTo("GymWorkoutData");
	}
	
/* 	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->isUnique(['member_id','record_date'],'Record for this date already exists.'));
		return $rules;
	} */
}

