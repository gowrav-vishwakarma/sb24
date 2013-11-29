<?php

class page_history_page_search extends page_base_site {
	function init(){
		parent::init();

		$this->api->stickyGET('search');

		// if($_GET['reset']){
		// 	// $this->forget('filter');
		// 	// $this->forget('state');
		// 	// $this->forget('city');
		// 	// $this->forget('tehsil');
		// 	// $this->forget('area');
		// 	// $this->forget('placetype');
		// 	$this->forget('search');
		// }
		// // $this->memorize("filter",$_GET['filter']?:$this->recall('filter',false));
		// $this->memorize("state",$_GET['state']?:$this->recall('state',false));
		// $this->memorize("city",$_GET['city']?:$this->recall('city',false));
		// $this->memorize("tehsil",$_GET['tehsil']?:$this->recall('tehsil',false));
		// $this->memorize("area",$_GET['area']?:$this->recall('area',false));
		// $this->memorize("placetype",$_GET['placetype']?:$this->recall('placetype',false));
		// $this->memorize("search",$_GET['search']?:$this->recall('search',false));

		$this->add('View_ModuleHeading');//->setHTML('Find Places <br/>( History )')->sub('Search via State, City and Place Type');
		$fields = array(
				// 'state_id'=>array('type'=>'dropdown', 'model'=>'State', 'emptyText' =>'Select State','span'=>2),
				// 'city_id'=>array('type'=>'dropdown', 'model'=> 'City', 'emptyText' =>'Select City','span'=>2),
				// 'tehsil_id'=>array('type'=>'dropdown', 'model' =>'Tehsil', 'emptyText' =>"Select Tehsil",'span'=>2),
				// 'area_id'=>array('type'=>'dropdown', 'model' =>'Area', 'emptyText' =>"Select Area",'span'=>2),
				// 'placetype_id'=>array('type'=>'dropdown','model'=>'history/PlaceType', 'emptyText'=>'Select PlaceType','span'=>3,'caption'=>'Type of Place'),
				'search'=>array('type'=>'line','span'=>10,'caption'=>" ")
			);

		// $chain_fields=array(
		// 		'city_id'=>'state_id',
		// 		'tehsil_id'=>'city_id',
		// 		'area_id'=>'tehsil_id'
		// 	);

		$form = $this->add('SearchForm',array('fields'=>$fields));
		$search_field=$form->getElement('search')->setAttr('placeholder','search in description and information')->addClass('leftmargin span8');
		$form->setFormClass('stacked atk-row');
            $o=$form->add('Order')
                ->move($form->addSeparator('noborder atk-row1'),'first')
                ->move($form->addSeparator('noborder atk-row1'),'after','search')
                ->now();

		$list = $this->add('history/View_Lister');
		$result = $this->add('history/Model_Place');
		
		// if($xfilter = $this->recall('filter',false)){
		// 	if($state = $this->recall('state',false)) $result->addCondition('state_id',$state);
		// 	if($city = $this->recall('city',false)) $result->addCondition('city_id',$city);
		// 	if($tehsil = $this->recall('tehsil',false)) $result->addCondition('tehsil_id',$tehsil);
		// 	if($area = $this->recall('area',false)) $result->addCondition('area_id',$_GET['area']);
		// 	if($area = $this->recall('placetype',false)) $result->addCondition('placetype_id',$_GET['placetype']);
		// }

		// if(!$filter = $this->recall('filter',false)) {
		// 	$list->template->tryDel('not_found');
		// 	$result->addCondition('id',-1);
		// }


		if($search = $_GET['search']){
			// throw new Exception("Error Processing Request".$form->get('search'));
			
			$result->addExpression('relevance')->set('MATCH(search_string) AGAINST("'.str_replace('"', '\"', $search).'" IN BOOLEAN MODE)');
			$result->addCondition('relevance','<>',0);
			$result->setOrder('relevance','desc');
		}else{
			$list->template->tryDel('not_found');
			$result->addCondition('id',-1);
		}

		$list->setModel($result);
		$list->addPaginator(10);

		if($form->isSubmitted()){
			// throw new Exception("Error Processing Request".$form->get('search'));
			
			// $this->forget('filter');
			// $this->forget('state');
			// $this->forget('city');
			// $this->forget('tehsil');
			// $this->forget('area');
			// $this->forget('placetype');
			// $this->forget('search');

			$list->js()->reload(array(
					// 'state'=>$form['state_id'],
					// 'city'=>$form['city_id'],
					// 'tehsil'=>$form['tehsil_id'],
					// 'area'=>$form['area_id'],
					// 'placetype'=>$form['placetype_id'],
					'search'=>$form->get('search')
					// 'filter'=>'1'
				))->execute();
		}


		$search_field->set($_GET['search']);

	}
}