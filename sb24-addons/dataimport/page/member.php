<?php

class page_dataimport_page_member extends Page {
	function init(){
		parent::init();

		$listing = $this->add('businessdirectory/Model_Listing');
		foreach ($listing as $junk) {
			$member=$this->add('Model_Member');
			$member['name']=$listing['contact_person'];
			$member['username']=($listing['email_id'] != "")?:"member_".$last_id;
			$member['pasword']=rand(1000,9999);
			$member['mobile_no']=$listing['mobile_no'];
			$member['state_id']=$listing['state_id'];
			$member['city_id']=$listing['city_id'];
			$member->save();
			$last_id = $listing['member_id']=$member->id;
			$listing->save();
		}

	}
}