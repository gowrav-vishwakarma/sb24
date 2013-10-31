<?php

class page_dataimport_page_oldlisting extends page_base_admin {
	function init(){
		parent::init();

		return;

		$old_listing = $this->add('dataimport/Model_OldListing')->dsql()->getAll();

		$new_listing = $this->add('businessdirectory/Model_Listing');
		$new_listing_test = $this->add('businessdirectory/Model_Listing')->dsql()->del('field')->field('name')->get();
		

		foreach($old_listing as $l){
			// Handle Category and subcategory, get subcategory_id and category_id
			if(in_array(array("name"=> $l['companyname']), $new_listing_test))	 continue;

			$ind_seg = $this->getIndustryAndSegmentID($l['category'],$l['subcategory']);
			// Get area id, city id and state id
			$loc = $this->getStateCityTehsilAreaID($l['state'],$l['city'],$l['tehsil'],$l['area']);
			// Make new entry
			$new_listing['industry_id'] = $ind_seg['industry_id'];
			$new_listing['segment_id'] = $ind_seg['segment_id'];
			
			$new_listing['state_id'] = $loc['state_id'];
			$new_listing['city_id'] = $loc['city_id'];
			$new_listing['tehsil_id'] = $loc['tehsil_id'];
			$new_listing['area_id'] = $loc['area_id'];

			$new_listing['name'] = $l['companyname'];
			$new_listing['company_address'] = $l['companyaddress'];
			$new_listing['mobile_no'] = $l['mobileno'];
			$new_listing['company_ph_no'] = $loc['phoneno1']. $loc['phoneno2'] ;
			$new_listing['address'] = $l['companyaddress'];
			$new_listing['email_id'] = $l['emailid'];
			$new_listing['website'] = $l['website'];

			$new_listing['contact_person'] = $l['name'];
			$new_listing['designation'] = 'authorized-person';
			$new_listing['contact_person_contact_number'] = $l['contactno'];

			$new_listing['created_on'] = date('Y-m-d H:i:s',strtotime($l['date']));
			$new_listing['last_paid_on'] = date('Y-m-d H:i:s',strtotime($l['date']));
			$new_listing['is_paid'] = ($l['dtae'] > 0 )? "1":"0";
			$new_listing['is_active'] = true;

			$new_listing->save();
				$payment = $new_listing->ref('businessdirectory/PayAmount');
				$payment['name'] = $l['amount'];
				$payment['submitted_on'] = date('Y-m-d H:i:s',strtotime($l['date']));

				$payment->saveAndUnload();
			$new_listing->unload();

		}

	}

	function getIndustryAndSegmentID($category,$subcategory){
		// check in new industry and sagmen table.. 
		// insert if not found
		// Load if found
		// return array of IDs 
		$industry = $this->add('businessdirectory/Model_Industry');
		$industry->addCondition('name',$category);
		$industry->tryLoadAny();
		if(!$industry->loaded()) $industry->save();

		$segment = $this->add('businessdirectory/Model_Segment');
		$segment->addCondition('name',$subcategory);
		$segment->addCondition('industry_id',$industry->id);
		$segment->tryLoadAny();
		if(!$segment->loaded()) $segment->save();

		return array(
			'industry_id'=>$industry->id,
			'segment_id'=>$segment->id
			);

	}

	function getStateCityTehsilAreaID($state_name,$city_name,$tehsil_name,$area_name){
		// check if area exists
		// load and return array of IDs
		
		$area = $this->add('Model_Area');
		// $area->addCondition('state_id',$state->id);
		// $area->addCondition('city_id',$city->id);
		// $area->addCondition('tehsil_id',$tehsil->id);
		$area->addCondition('name',$area_name);
		$area->tryLoadAny();
		// if(!$area->loaded()) $area->save();

		return array(
				'state_id'=>$area['state_id'],
				'city_id'=>$area['city_id'],
				'tehsil_id'=>$area['tehsil_id'],
				'area_id' => $area['id']
			);

		$state = $this->add('Model_State');
		$state->addCondition('name',$state_name);
		$state->tryLoadAny();
		if(!$state->loaded()) $state->save();

		$city = $this->add('Model_City');
		$city->addCondition('state_id',$state->id);
		$city->addCondition('name',$city_name);
		$city->tryLoadAny();
		if(!$city->loaded()) $city->save();

		$tehsil = $this->add('Model_Tehsil');
		$tehsil->addCondition('state_id',$state->id);
		$tehsil->addCondition('city_id',$city->id);
		$tehsil->addCondition('name',$tehsil_name);
		$tehsil->tryLoadAny();
		if(!$tehsil->loaded()) $tehsil->save();


		return array(
				'state_id'=>$state->id,
				'city_id'=>$city->id,
				'tehsil_id'=>$tehsil->id,
				'area_id' => $area->id
			);

	}
}