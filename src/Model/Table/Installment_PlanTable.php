<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class Installment_PlanTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		// $this->displayField("duration");
		// $this->belongsTo("Membership");
		$this->hasMany("Membership");
	}
}