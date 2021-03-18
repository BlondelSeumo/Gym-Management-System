<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class ClassBookingTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->primaryKey('booking_id');
		$this->belongsTo("ClassSchedule");
		$this->belongsTo("ClassScheduleList");
		
	}
}