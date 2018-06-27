<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class loginUser extends CI_Controller {

	public function __construct()
 
	{
	 
	parent::__construct();
	 
	$this->load->helper('url');

	$this->load->helper('cookie');
	 
	$this->load->model('User_model');
	 
	}

	/**
		用户登录的处理功能
		基本逻辑：先验证验证码，然后在验证相关的输入
	*/
	public function index()
	{
		 $username  = trim($_POST['user']);
		 $password = trim($_POST['pass']);
		// $inputcode =  strtoupper(trim($_POST['code']));
		 $inputcode =  strtoupper(trim($_POST['inputcode']));

		$returnArr=array();

		$users = $this->User_model->getByUserName('user',$username);

		$code = strtoupper(trim($_COOKIE['code']));

		if($code != $inputcode){

			$returnArr['success'] =0;

            $returnArr['message'] ='验证码错误';

            echo json_encode($returnArr);

            exit();

		}

		if(sizeof($users) > 0){

			$dPass = $users['0']['password'];

			 if($dPass == $password){

			 	$returnArr['success'] =1;

                $returnArr['message'] ='登录成功';

                $returnArr['user'] = $username;

                $_SESSION['user'] = $username;

			 }
		}

		if(!isset($returnArr['success'])){

			$returnArr['success'] =0;

            $returnArr['message'] ='用户名/密码错误';
		}

		echo json_encode($returnArr);
	}


}