<?php

class page_dataimport_page_stdcode extends page_base_site {
	
	function init(){
		parent::init();

		return;

		$old_std = $this->add('dataimport/Model_STDCode');

		$new_std = $this->add('tracker/Model_STDListing');

		foreach($old_std as $junk){
			$state = $this->add('tracker/Model_STDState');
			$state->addCondition('name',$old_std['state']);
			$state->tryLoadAny();
			if(!$state->loaded()) $state->save();
			$district = $this->add('tracker/Model_STDDistrict');
			$district->addCondition('name',$old_std['district']);
			$district->addCondition('state_id',$state->id);
			$district->tryLoadAny();
			if(!$district->loaded()) $district->save();

			$new_std['state_id'] = $state->id;
			$new_std['district_id'] = $district->id;
			$new_std['area'] = $old_std['area'];
			$new_std['STD_code'] = $old_std['stdcode'];
			$new_std->saveAndUnload();
			$state->unload();
			$district->unload();

		}


	}
}