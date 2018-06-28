<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {

	public function index()
	{
		//session_start(); 
		$data = '';

		if(isset($_SESSION['user'])){

		 	$data['user'] = $_SESSION['user'];
		 }

		$this->load->view('login_message',$data);
	}

	public function test(){

		echo $_COOKIE['code'];
	}

}