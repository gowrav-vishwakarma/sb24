<?php

class page_dataimport_page_pincode extends page_base_site {
	function init(){
		parent::init();

		return;
		// SELECT DISTINCT STATE => PUT IN PINCODESTATE TABLE


		// $stats = $this->api->db->dsql()->table('pincode_Listing')->del('field')->field('DISTINCT(state_id) state')->getAll();
		// $state_ids=array();

		// $state_save = $this->add('tracker/Model_PINCODEState');			
		// foreach ($stats as $state) {
		// 	$state_save['name']=$state['state'];
		// 	$state_save->save();
		// 	$this->api->db->dsql()->table('pincode_Listing')->set('state_id',$state_save->id)->where('state_id',$state['state'])->execute();
		// 	$state_save->unload();
		// }

		// // SELECT DISTINCT DISTRICT => PUT IN PINCODEDISTRICT
		// $districts = $this->api->db->dsql()->table('pincode_Listing')->del('field')->field('DISTINCT(district_id) district')->get();

		// $district_ids = array();

		// $dist_save = $this->add('tracker/Model_PINCODEDistrict');			
		// foreach ($districts as $dist) {
		// 	$dist_save['name']=$dist['district'];
		// 	$dist_save->save();
		// 	$district_ids += array($dist['district'] => $dist_save->id);
		// 	$this->api->db->dsql()->table('pincode_Listing')->set('district_id',$state_save->id)->where('district_id',$dist['district'])->execute();
		// 	$dist_save->unload();
		// }


		// FOREACH STATE
			// REPLACE STATE with ID WHERE NAME from foreach

		// FOREACH DISTRICT
			// REPLACE DISTRICT with ID WHERE NAME from foreach

	}
}