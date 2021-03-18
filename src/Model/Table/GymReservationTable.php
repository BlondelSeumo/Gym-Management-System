<?php 
namespace App\Model\Table;
use Cake\ORM\Table;
Class GymReservationTable extends Table
{
	public function initialize(array $config)
	{
		$this->belongsTo("GymEventPlace",["foreignKey"=>"place_id"]);
	}
	
}