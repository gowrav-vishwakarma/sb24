<?php

$temp =explode('\\', $_GET['addon_page']);

$tc="class page_addonpage extends ".$temp[0]."\\page_".$temp[1] ."{}";
eval($tc);