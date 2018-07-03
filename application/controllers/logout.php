<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class logout extends CI_Controller {

	public function __construct(){
	 
		parent::__construct();

		$this->load->model('User_model');
	 
	}

	/*获取登录用户的基本信息*/
	public function index(){



		// $username = $_SESSION['user'];
		
		// $users = $this->User_model->getByUserName('user',$username);	 

		// unset($_SESSION);
		 
  //   	session_destroy(); 

		setcookie('username', NULL,-1,'/MessageByCI/');

		setcookie('token', NULL,-1,'/MessageByCI/');

    	echo "<script>window.location.href='./login/'</script>";
	}

}