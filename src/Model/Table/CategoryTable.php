<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class categoryTable extends Table{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");		
	}	
}