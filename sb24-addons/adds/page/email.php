<?php

class page_adds_page_email extends page_base_null{
	function init(){
		parent::init();

		if($_GET['email_to']=="") return;
		// check if email is valid or not here  if yes then return

		$tm=$this->add( 'TMail_Transport_PHPMailer' );
		$msg=$this->add( 'SMLite' );
		$msg->loadTemplate( 'mail/adclicked' );

		$email_body=$msg->render();

		$subject ="Your Ad was clicked on Sabkuch24.com";

		try{
			$tm->send( $_GET['email_to'], "info@epan.in", $subject, $email_body);
		}catch( phpmailerException $e ) {
			throw $e;
			$this->api->js()->univ()->errorMessage( $e->errorMessage() )->execute();
		}catch( Exception $e ) {
			throw $e;
		}

		$this->js(true)->univ()->successMessage("Done")->execute();
	}
}