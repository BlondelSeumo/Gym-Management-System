<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class GymNutritionTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("GymMember",["foreignKey"=>"user_id"]);		
		$this->belongsTo("GymNutritionData");		
	}
}