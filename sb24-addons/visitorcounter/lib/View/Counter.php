<?php

namespace visitorcounter;

class View_Counter extends \View{
	
	function init(){
		parent::init();

		$counter = $this->add('visitorcounter/Model_Counter');
		$counter->addCondition('on_date',date('Y-m-d'));
		$counter->tryLoadAny();
		if(!$counter->loaded())
			$counter->save();

		if(!$this->api->recall('is_repeated',false)){
			$counter['visits'] = $counter['visits'] +1 ;
			$counter->save();
			$this->api->memorize('is_repeated',true);	
		}

		$this->set($counter['visits']);

	}
}