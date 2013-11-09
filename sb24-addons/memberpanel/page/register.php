<?php

class page_memberpanel_page_register extends page_base_site {
	function init(){
		parent::init();

		$this->add('H3')->set('Register Now, Its Free ...')
			->sub('Add your own free listings, get unlimited informations ... ');

		$model=$this->add('Model_Member');
		$model->getElement('password')->system(true);

		$form = $this->add('Form');
		$form->setModel($model,'base');
		$form->addField('checkbox','i_agree','&nbsp;I agree to sabkuch24.com Terms of services and privcy policy, Subscriber to E-mail and sms alert');
		$form->add('Button',null,null,array('view/mybutton','button'))->set('Register')->addStyle(array('margin-top'=>'25px','margin-left'=>'398px'))->addClass(' shine1')->js('click')->submit();

		if($form->isSubmitted()){
			if(!$form->get('i_agree'))
				$form->displayError('i_agree','please check');
			$form->update();
			$form->js(null, $this->js()->univ()->redirect('memberpanel_page_dashboard'))->univ()->closeDialog()->execute();
		}
	}
}