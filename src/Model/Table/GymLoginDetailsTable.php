<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;

Class GymLoginDetailsTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');		
	}
	
	public function buildRules(RulesChecker $rules)
	{		
		$rules->add($rules->isUnique(['username'],'Username already in use.'));
		return $rules;
	}
}