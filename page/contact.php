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


		$col2->add('H3')->setHtml('<span style="color:firebrick;">For More Information Contact Us </span>');
		$col2->add('H3')->setHTML('<span style="color:green; align:center">Safal Sansthan</span>');
		$col2->add('View')->setHTML('<span style="color:red; align:center">Contact Details of SabKuch24.com</span>');
		$col2->add('View')->setHTML('<span style="color:blue; align:center"><b> Address :</b></span><span style="color:black"> 452-A, Moksh Marg, Bhupalpura Nr. R.K. Plaza, Shastri Circle, Udaipur (Raj.) - 313001</span>');
		$col2->add('View')->setHTML('<span style="color:blue; align:center"><b> Email :</b></span><span style="color:black"> sabkuch24@gmail.com;</span>');
		$col2->add('View')->setHTML('<span style="color:blue; align:center"><b> Website :</b></span><span style="color:black"> www.sabkuch24.com</span>');
		$col2->add('View')->setHTML('<span style="color:blue; align:center"><b> Mobile :</b></span><span style="color:black"> +91 992 801 8585</span>');
		$col2->add('H3')->setHTML('<span class="shine1">Helpline No. +91 921 421 2200 </span>');

		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$form->js()->univ()->successMessage('Successfully Submitted!'))->reload()->execute();
		}
	}
}