<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class MembershipPaymentTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->primaryKey('mp_id');
		$this->belongsTo("GymMember");
		$this->belongsTo("Membership",["foreignKey"=>"membership_id"]);
		$this->belongsTo("MembershipPayment",["foreignKey"=>"membership_id"]);
		$this->belongsTo("MembershipPaymentHistory");
		$this->belongsTo("MembershipHistory");
		$this->belongsTo("GymIncomeExpense");
		$this->belongsTo("GymMember",["foreignKey"=>"member_id"]);
	}
}