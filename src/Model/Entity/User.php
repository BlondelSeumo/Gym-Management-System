<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Users extends Entity
{
	protected $_accessible = ['*'=> true,'id' => false];

	protected function _setPassword($password)
	{
		return (new DefaultPasswordHasher)->hash($password);
	}
}

?>