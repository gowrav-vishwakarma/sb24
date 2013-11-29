<?php

$config['atk']['base_path']='./atk4/';
$config['dsn']='mysql://root:winserver@127.0.0.1/sb24';

/*$config['dsn']=array('type'=>'mysql',
             'hostspec'=>'127.0.0.1:7188',
             'username'=>'root',
             'password'=>'winserver',
             'database'=>'bill',
             'charset'=>'utf-8');
*/
$config['url_postfix']='';
$config['url_prefix']='?page=';
// $config['js']['versions']['jquery'] = "1.8.3.min";

# Agile Toolkit attempts to use as many default values for config file,
# and you only need to add them here if you wish to re-define default
# values. For more options look at:
#
#  http://www.atk4.com/doc/config

$config['tmail']['transport'] = "PHPMailer";
$config['tmail']['phpmailer']['from'] = "help3gift@gmail.com";
// $config['tmail']['from'] = "help3gift@gmail.com";
$config['tmail']['phpmailer']['from_name'] = "3Gift System";
$config['tmail']['smtp']['host'] = "ssl://smtp.gmail.com";
$config['tmail']['smtp']['port'] = 465;
$config['tmail']['phpmailer']['username'] = "help3gift@gmail.com";
$config['tmail']['phpmailer']['password'] = "helphelphelp3gift";
$config['tmail']['phpmailer']['reply_to'] = "help3gift@gmail.com";
$config['tmail']['phpmailer']['reply_to_name'] = "3Gift System";