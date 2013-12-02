<?php
class page_contact extends page_base_site{
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$col1=$cols->addColumn(6);
		$col2=$cols->addColumn(6);
		$col1->add('H1')->setHTML('<span style="color:firebrick">Feedback Form</span>');
		$form=$col1->add('Form');
		// $form->addField('line','name');
		// $form->addField('line','mobile_no');
		// $form->addField('line','email');
		// $form->addField('text','message');
		$form->setModel('Contact');
		$form->addSubmit('Submit Feedback');


		$col2->add('H3')->setHtml('<span style="color:firebrick;">For more Information Contact us </span>');
		$col2->add('H3')->setHTML('<span style="color:green; align:center">Safal Sansthan</span>');
		$col2->add('View')->setHTML('<span style="color:green; align:center">Contact Details of SabKuch24.com</span>');

		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$form->js()->univ()->successMessage('Successfully Submitted!'))->reload()->execute();
		}
	}
}