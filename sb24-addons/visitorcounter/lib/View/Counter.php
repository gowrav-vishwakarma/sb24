<?php

namespace visitorcounter;

class View_Counter extends \View{
	
	function init(){
		parent::init();

		if($this->api->recall('is_repeated',false)){
			$counter = $this->add('Model_Counter');
			
		}

	}
}