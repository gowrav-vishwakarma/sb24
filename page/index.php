<?php
class page_index extends page_base_site {
	function init(){
		parent::init();

		$this->api->template->tryDel('top_advert_position');
		$this->api->template->tryDel('left_advert_position');
		$this->api->template->tryDel('right_advert_position');
		$this->api->template->trySet('center_span',12);
		$this->api->template->tryDel('content_class');
		$this->api->template->tryDel('body_margin');
		

		$this->add('HtmlElement')->setElement('img')->setAttr('src',$this->api->sb24_config['front_image'])->setAttr('align','center');
		$search_form=$this->add('Form')->addClass('stacked');
		$search_form->addField('line','search','')->addClass('span10');
		$search_form->add('Button')->set('Search')->addClass('shine atk-form-row atk-form-row-dropdown span3 span3')->js('click')->submit();
		if($search_form->isSubmitted()){
			$search_form->js()->univ()->redirect($this->api->url('businessdirectory_page_search',array('reset'=>1,"filter"=>1,'search'=>$search_form['search'])))->execute();
		}

		$cols=$this->add('Columns')->addClass('right-front-page-col');
		$col_login=$cols->addColumn(8);
		// $col_register=$cols->addColumn(7)->addClass('col');


		// $col_register->add('H3')->set('Register Now, Its Free ...')
		// 	->sub('Add your own free listings, get unlimited informations ... ')->setStyle('color','burlywood');

		// $model=$this->add('Model_Member');
		// $model->getElement('password')->system(true);

		// $form_register = $col_register->add('Form');
		// $form_register->setModel($model,'base');
		// $form_register->addSubmit("Register")->addClass('shine');

		// if($form_register->isSubmitted()){
		// 	$form_register->update();
		// 	$form_register->js(null, $this->js()->univ()->redirect('memberpanel_page_dashboard'))->univ()->closeDialog()->execute();
		// }


		$col_login->add('H3')->set('Login to Your Account')->setStyle('color','burlywood');
		$login_form=$col_login->add('Form');
		$login_form->addField('line','username');
		$login_form->addField('password','password');
		$login_form->addSubmit('Log In')->addClass('shine');
		$col_login->add('H5')->set('Forget Password')->setStyle('text-decoration','underline')->addStyle('cursor','help')->js('click',$this->js()->univ()->frameURL("Forgot Password !!!",$this->api->url('memberpanel_page_forgetpassword')));

		if($login_form->isSubmitted()){
			$this->api->auth->setModel('Member','username','password');
			if(!$this->api->auth->verifyCredentials($login_form['username'],$login_form['password'])){
				$login_form->displayError('password','Incorrect login information');
			}
			$this->api->auth->loginBy('username',$login_form['username']);
			$this->js()->univ()->redirect('memberpanel_page_dashboard')->execute();
		}
	}


	function defaultTemplate(){
		return array('page/index');
	}
}