<?php

class page_salesandpurchase_page_more extends page_base_site{
	function init(){
		parent::init();

		$listing=$this->add('salesandpurchase/Model_Listing');
		$listing->load($_GET['listing_id']);
		
		$tabs=$this->add('Tabs');
		$tab_about=$tabs->addTab('About Product');
		$tab_image=$tabs->addTab('Product Images');

		$tab_about->add('View')->setHTML($listing['description']);

		$tab_image->add('H2')->set("Gallary");

		$image_cols=$tab_image->add('Columns');
		for ($i=1; $i<=5; $i++) { 
			$col=$image_cols->addColumn(2);			
			$col->add('HtmlElement')->setElement('img')->setAttr('src',$listing["product_image_".$i])->addClass('gallary');
		}
		
	}
}