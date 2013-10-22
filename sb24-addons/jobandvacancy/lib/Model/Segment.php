<?php

namespace jobandvacancy;

class Model_Segment extends \Model_Table {
	var $table= "jobandvacancy_segment";
	function init(){
		parent::init();

		$this->addField('name')->caption('Segment');
		$this->hasMany('jobandvacancy/Listing','segment_id');
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this->ref('jobandvacancy/Listing')->count()->getOne() > 0)
			throw $this->exception("You can not delete this Segment, it contains listing of job & vacany");
			
	}
}