<?php

class page_dataimport_page_area extends Page {
	function init(){
		parent::init();

		return;

		$district = $this->add('dataimport/Model_Areas')->dsql()->del('field')->field('distinct(district) dist')->having('dist <> ""')->getAll();

		foreach ($district as $junk) {
			$counts = $this->add('dataimport/Model_Areas')->dsql()->del('field')->field('count(DISTINCT(tehsil)) total_tesils')->field('count(*) total_areas')->where('district',$junk['dist'])->getRow();
			$btn = $this->add('View')->add('Button')->set('Import ' . $junk['dist'] . " " . $counts[0] . " " . $counts[1]);
			if($_GET['reloaded'] == $junk['dist']) $btn->set('Done');
			if($btn->isClicked()){
				$this->importDistrict($junk['dist']);
				$btn->js()->reload(array('reloaded'=>$junk['dist']))->execute();
			}
		}

	}

	function importDistrict($district,$limit=100){
		// Check if this district exists.. if not .. insert
		$new_dist = $this->add('Model_City');
		$new_dist->addCondition('name',$district);
		$new_dist->addCondition('state_id',1);
		$new_dist->tryLoadAny();
		if(!$new_dist->loaded()) $new_dist->save();

		$this->importTehsils($district,$new_dist->id, 1);

	}

	function importTehsils($district, $new_district_id, $state_id){
		// Get all Tehsils of this district
		$old_teh = $this->add('dataimport/Model_Areas')->dsql()->del('field')->field('distinct(tehsil) tehsil')->where('district',$district)->having('tehsil <> ""')->getAll();
		// print_r($old_teh);
		// return;
		// Insert into Tehsils
		$new_teh = $this->add('Model_Tehsil');
		foreach ($old_teh as $junk) {
			$new_teh['city_id']=$new_district_id;
			$new_teh['name'] = $junk['tehsil'];
			$new_teh->save();
			$this->importAreas($junk['tehsil'],$new_teh->id,$new_district_id,$state_id);
			$new_teh->unload();
		}
	}

	function importAreas($Tehsil, $new_tehsil_id, $new_district_id, $state_id){
		// Get All Areas of old tehsild
		$old_area = $this->add('dataimport/Model_Areas')->addCondition('tehsil',$Tehsil);
		// Insert in Areas table
		$new_area= $this->add('Model_Area');
		foreach ($old_area as $junk) {
			$new_area['city_id'] = $new_district_id;
			$new_area['tehsil_id'] = $new_tehsil_id;
			$new_area['name'] = $old_area['area'];
			$new_area->saveAndUnload();
		}

	} 
}