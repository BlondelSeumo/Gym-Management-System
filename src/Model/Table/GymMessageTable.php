<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class GymMessageTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("ClassSchedule");
		$this->belongsTo("GymMember",["foreignKey"=>"sender"]);
	}
}