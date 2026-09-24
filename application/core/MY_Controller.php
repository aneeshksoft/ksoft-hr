<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->set_timezone();
    }

    public function set_timezone() {
        
        $timezone = $this->settings->get_settings('timeZone');
        date_default_timezone_set($timezone);
        
    }
}