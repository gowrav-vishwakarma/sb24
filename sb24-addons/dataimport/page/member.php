<?php

class page_dataimport_page_member extends Page {
	function init(){
		parent::init();

		return;

		$listing = $this->add('businessdirectory/Model_Listing')->count()->getOne();

		$last_id=1;
		$member=$this->add('Model_Member');
		foreach ($listing as $junk) {
			$member['name']=$listing['contact_person'];
			$member['username']="member_".$last_id;
			$member['pasword']=rand(1000,9999);
			$member['mobile_no']=$listing['mobile_no'];
			$member['state_id']=$listing['state_id'];
			$member['city_id']=$listing['city_id'];
			$member->save();
			$last_id = $listing['member_id']=$member->id;
			$listing->saveAndUnload();
			$member->unload();
		}
	}

	function createMember($from){
		$listing = $this->add('businessdirectory/Model_Listing')
					->setLimit(100,$from);
		$last_id=$from;
		$member=$this->add('Model_Member');
		foreach ($listing as $junk) {
			$member['name']=$listing['contact_person'];
			$member['username']="member_".$last_id;
			$member['pasword']=rand(1000,9999);
			$member['mobile_no']=$listing['mobile_no'];
			$member['state_id']=$listing['state_id'];
			$member['city_id']=$listing['city_id'];
			$member->save();
			$last_id = $listing['member_id']=$member->id;
			$listing->saveAndUnload();
			$member->unload();
		}
	}
}