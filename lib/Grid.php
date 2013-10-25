<?php

class Grid extends Grid_Advanced{
	function format_picture($field){
		$this->current_row_html[$field]="<img src='". $this->current_row[$field]. "' style='max-width:100px'>";
	}
}