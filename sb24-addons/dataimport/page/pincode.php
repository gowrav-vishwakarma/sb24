<?php

class page_dataimport_page_pincode extends page_base_site {
	function init(){
		parent::init();

		// SELECT DISTINCT STATE => PUT IN PINCODESTATE TABLE
		$stats = $this->api->db->dsql()->table('pincode_Listing')->del('field')->field('DISTINCT(state_id) state')->getAll();

		print_r($stats);

		// foreach ($stats as $state) {
			
		// }

		// SELECT DISTINCT DISTRICT => PUT IN PINCODEDISTRICT
		$districts = $this->api->db->dsql()->table('pincode_Listing')->del('field')->field('DISTINCT(district_id) district')->get();
		print_r($districts);


		// FOREACH STATE
			// REPLACE STATE with ID WHERE NAME from foreach

		// FOREACH DISTRICT
			// REPLACE DISTRICT with ID WHERE NAME from foreach

	}
}