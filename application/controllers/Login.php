<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {

	public function __construct(){
	 
		parent::__construct();

		$this->load->helper('cookie');
	 
		$this->load->model('User_model');
	 
	}

	public function index(){
		
		$this->load->view('login_message');
	}

	public function test(){

		$user = $this->User_model->getCookieUser();

		echo $user;
	}

}