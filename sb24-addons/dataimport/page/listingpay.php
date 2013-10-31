<?php

class page_dataimport_page_listingpay extends Page {
	function init(){
		parent::init();

		$old_listing = $this->add('dataimport/Model_OldListing');
		$old_listing->addCondition('amount','>',0);

		foreach($old_listing as $junk){
			$new_listing = $this->add('businessdirectory/Model_Listing');
			$new_listing->addCondition('name',$old_listing['companyname']);
			$new_listing->loadAny();

			$payment = $new_listing->ref('businessdirectory/PayAmount');
			$payment['name'] = $old_listing['amount'];
			$payment['submitted_on'] = date('Y-m-d H:i:s',strtotime($old_listing['date']));
			$payment->saveAndUnload();

			$new_listing->destroy();

		}

	}
}