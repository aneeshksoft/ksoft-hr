<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

$autoload['libraries'] = array('database','email','session','form_validation','CI_Minifier','settings');

$autoload['drivers'] = array();

$autoload['helper'] = array('url','form','file','security','custom_helper','mail_helper');

$autoload['config'] = array('jwt');

$autoload['language'] = array();

$autoload['model'] = array('Common_model'=>'common_model','Leave_model'=>'leave_model');

function pr($data) {
	echo '<pre>';print_r($data);echo '</pre>';
}