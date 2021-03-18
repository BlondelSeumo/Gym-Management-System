<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class GymIncomeExpenseTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->belongsTo("GymMember",["foreignKey"=>"supplier_name"]);
	}
}