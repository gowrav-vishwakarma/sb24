<?php


class page_dataimport_page_distance extends Page {
	function init(){
		parent::init();

		$cities_done=array();
		$cities_array=array();

		$city =$this->add('distance/Model_City');
		foreach($city as $junk){
			$cities_done[] = $city['name'];
			$cities_array[$city['name']] = $city->id;
		}
		

		$old_dist_all = $this->add('dataimport/Model_Distance')->_dsql()->getAll();
		$new_dist=$this->add('distance/Model_Listing');

		$city =$this->add('distance/Model_City');
		foreach ($old_dist_all as $old_dist) {
			if(!in_array($old_dist['from'], $cities_done)){
				$city['name']=$old_dist['from'];
				$city->saveAndUnload();
				$cities_done[] = $old_dist['from'];
			}
			if(!in_array($old_dist['to'], $cities_done)){
				$city['name']=$old_dist['to'];
				$city->saveAndUnload();
				$cities_done[] = $old_dist['to'];
			}

			$from_id=$cities_array[$old_dist['from']];
			$to_id=$cities_array[$old_dist['to']];

			try{
				$new_dist['city_1_id']=$from_id;
				$new_dist['city_2_id']=$to_id;
				$new_dist['distance_bus']=$old_dist['road'];
				$new_dist['distance_train']=$old_dist['rail'];
				$new_dist['distance_plane']=$old_dist['air'];
				$new_dist->saveAndUnload();
			}catch(Exception_ValidityCheck $e){

			}
			
		}

	}
}