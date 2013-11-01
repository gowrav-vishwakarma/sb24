<?php
class page_index extends page_base_site {
	function init(){
		parent::init();

		$this->api->template->tryDel('top_advert_position');
		$this->api->template->tryDel('left_advert_position');
		$this->api->template->tryDel('right_advert_position');
		$this->api->template->trySet('center_span',12);
		$this->api->template->tryDel('content_class');
		// $cols=$this->add('Columns');
		// $col1=$cols->addColumn(1)->add('View')->setHtml('&nbsp');
		// $col2=$cols->addColumn(5);//->addStyle(array("margin-top"=>"150px","margin-left"=>"450px"));
		// $col3=$cols->addColumn(2);
		$this->add('HtmlElement')->setElement('img')->setAttr('src',$this->api->sb24_config['front_image'])->setAttr('align','center');
		$search_form=$this->add('Form')->addClass('stacked');
		$search_form->addField('line','search','')->addClass('span10');
		$search_form->add('Button')->set('Search')->addClass('shine atk-form-row atk-form-row-dropdown span3 span3')->js('click')->submit();
		if($search_form->isSubmitted()){
			$search_form->js()->univ()->redirect($this->api->url('businessdirectory_page_search',array('reset'=>1,"filter"=>1,'search'=>$form['search'])))->execute();
		}

		$cols=$this->add('Columns')->addClass('right-front-page-col');
		$col_login=$cols->addColumn(5);
		$col_register=$cols->addColumn(7)->addClass('col');//->addClass('right-front-page-col');
		// ->setStyle(array('margin-left'=>'36.5%','background-color'=>'white','border'=>'10px solid burlywood','border-radius'=>'40px'));


		$col_register->add('H3')->set('Register Now, Its Free ...')
			->sub('Add your own free listings, get unlimited informations ... ');

		$model=$this->add('Model_Member');
		$model->getElement('password')->system(true);

		$form_register = $col_register->add('Form');
		$form_register->setModel($model,'base');
		$form_register->addSubmit("Register");

		if($form_register->isSubmitted()){
			$form_register->update();
			$form_register->js(null, $this->js()->univ()->redirect('memberpanel_page_dashboard'))->univ()->closeDialog()->execute();
		}


		$col_login->add('H1')->set('Login to Your Account');
		$login_form=$col_login->add('Form');
		$login_form->addField('line','username');
		$login_form->addField('password','password');
		$login_form->addSubmit('Log In');
	}


	function defaultTemplate(){
		return array('page/index');
	}
}