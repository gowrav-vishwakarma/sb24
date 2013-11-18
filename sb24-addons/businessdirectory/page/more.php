<?php

class page_businessdirectory_page_more extends page_base_site{
	function init(){
		parent::init();
		$this->api->stickyGET('listing_id');
		$listing=$this->add('businessdirectory/Model_Listing');
		$listing->load($_GET['listing_id']);


		if(!$listing['is_paid']){
			$this->add('View_Info')->set("Additional Information not available, as it is a free listing");
			return;
		}

		$tabs=$this->add('Tabs');
		$about=$tabs->addTab('About Company');
		$gallary=$tabs->addTab('Gallary');
		$p_and_s=$tabs->addTab('Products & Services');
		// $map=$tabs->addTab('Map');
		$contact=$tabs->addTab('Contact Us');

		$about->add('H2')->set('About '.$listing['name']);
		$about->add('View')->setHTML("Hello".$listing['about_us']);

		//gallary start//
		$gallary->add('H2')->set("Gallary");
		$gallary->add('businessdirectory/View_Gallary')->setModel($listing->ref("businessdirectory/GallaryImages"));

		// /gallary end//

		//products and services start//
		$p_and_s->add('H2')->set("Products & Services");
		$p_and_s->add('businessdirectory/View_Product')->setModel($listing->ref("businessdirectory/ProductImages"));
		
		//products and services end//

		//map//
		// $map->add('google/View_Map');

		//contact us

		$contact_cols=$contact->add('Columns');
		$contact_col1=$contact_cols->addColumn(8);
		$contact_col2=$contact_cols->addColumn(4);

		$contact_col2->add('HtmlElement')->setElement('img')->setAttr('src',$listing["company_logo"]);
		$contact_col2->add('View')->set($listing['company_address'])->setClass('text');

		$contact_col1->add('H2')->set('Enquiry Form');
		$contact_form=$contact_col1->add('Form');
		$contact_form->addField('line','name');
		$contact_form->addField('line','mobile_no');
		$contact_form->addField('line','email_id');
		$contact_form->addField('text','messge');
		$contact_form->addSubmit('Submit Enquiry');

		if($contact_form->isSubmitted()){
			$tm=$this->add( 'TMail_Transport_PHPMailer' );
			$msg=$this->add( 'SMLite' );
			$msg->loadTemplate( 'mail/enquiry' );
			$msg->trySet( 'name', $contact_form['name'] );
			$msg->trySet( 'mobile_no', $contact_form['mobile_no'] );
			$msg->trySet( 'email_id', $contact_form['email_id'] );
			$msg->trySet( 'message', $contact_form['messge'] );
			$email_body=$msg->render();

			$subject ="Your got an enquiry from Sabkuch24.com listing";

			try{
				$tm->send( $listing['email_id'], "info@sabkuch24.com", $subject, $email_body);
			}catch( phpmailerException $e ) {
				throw $e;
				$this->api->js()->univ()->errorMessage( $e->errorMessage() )->execute();
			}catch( Exception $e ) {
				throw $e;
			}
			$contact_form->js(null,$contact_form->js()->reload())->univ()->successMessage('Enquiry has been submitted')->execute();
		}
	}
}