<?php

namespace adds;

class Model_AdBlock extends \Model_Table {
	var $table= "adblock";
	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('order');
		$this->addField('rotation_time')->hint('No Of Seconds to rotate adds in this block')->defaultValue(5);
		$this->addField('position')->enum(array('Top','Left','Right'));
		$this->addField('height');
		$this->addField('is_active')->type('boolean')->defaultValue(true);

		$this->hasMany('adds/Ad');


		$this->add('dynamic_model/Controller_AutoCreator');
	}
}