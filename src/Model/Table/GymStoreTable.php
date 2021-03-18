<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class GymStoreTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("GymMember",["foreignKey"=>"member_id"]);
		$this->belongsTo("GymProduct",["foreignKey"=>"product_id"]);
	}
}