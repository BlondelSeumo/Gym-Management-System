<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

class GymUserWorkoutTable extends Table
{
	public function initialize(array $config)
	{
		
	}
	
/* 	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->isUnique(['member_id','record_date'],'Record for this date already exists.'));
		return $rules;
	} */
}

