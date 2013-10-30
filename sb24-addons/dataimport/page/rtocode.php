<?php

class page_dataimport_page_rtocode extends page_base_site {
	
	function init(){
		parent::init();

		return;

		$old_rto = $this->add('dataimport/Model_RTOCode');

		$new_rto = $this->add('tracker/Model_RTOListing');

		foreach($old_rto as $junk){
			
			if($old_rto['area']=="") continue;

			$state = $this->add('tracker/Model_RTOState');
			$state->addCondition('name',$old_rto['state']);
			$state->tryLoadAny();
			if(!$state->loaded()) $state->save();

			$new_rto['state_id'] = $state->id;
			$new_rto['area'] = $old_rto['area'];
			$new_rto['name'] = $old_rto['rtocode'];
			$new_rto->saveAndUnload();
			$state->unload();

		}


	}
}