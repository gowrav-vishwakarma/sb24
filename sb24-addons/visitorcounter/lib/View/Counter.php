<?php

namespace visitorcounter;

class View_Counter extends \View{
	
	function init(){
		parent::init();

		

		if(!$this->api->recall('is_repeated',false)){
			$counter = $this->add('visitorcounter/Model_Counter');
			$counter->addCondition('on_date',date('Y-m-d'));
			$counter->tryLoadAny();
			if(!$counter->loaded())
				$counter->save();
				$counter['visits'] = $counter['visits'] +1 ;
				$counter->save();
				$this->api->memorize('is_repeated',true);	
		}

		$counter_total = $this->add('visitorcounter/Model_Counter');
		$counter_today = $this->add('visitorcounter/Model_Counter');
		$counter_today->addCondition('on_date',date('Y-m-d'));
		$counter_today->tryLoadAny();

		$this->add('View')->set("Total Visitors : " . $counter_total->sum('visits')->getOne());
		$this->add('View')->set("Todays Visitors : " . $counter_today['visits']);
		$this->addClass('visitors_counter');

	}
}