<?php
namespace jobandvacancy;
class Model_MemberListing extends Model_Listing {
	function init(){
		parent::init();
		$this->addCondition('member_id',$this->api->auth->model->id);
		$this->addCondition('is_active',false);
		$this->getElement('created_on')->system(true);
		$this->getElement('valid_till')->system(true);

	}
}