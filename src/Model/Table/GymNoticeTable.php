<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class GymNoticeTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");		
		$this->belongsTo("GymMember");
		$this->belongsTo("ClassSchedule");
	}
}