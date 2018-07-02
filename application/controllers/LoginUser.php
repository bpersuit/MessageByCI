<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class loginUser extends CI_Controller {

	public function __construct(){
	 
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

		$code = strtoupper(trim($_COOKIE['code']));

		//验证字符串的合法性
		if(!preg_match("/^[0-9a-zA-Z]+$/",$code) ||  strlen($code) != 4 || $code != $inputcode){

			$returnArr['success'] =0;

            $returnArr['message'] ='验证码错误';

            echo json_encode($returnArr);

            exit();
		}

		$users = $this->User_model->getByUserName('user',$username);
	
		if(sizeof($users) > 0){

			$dPass = $users['0']['password'];

			 if($dPass == $password){

			 	$returnArr['success'] = $users['0']['id'];

                $returnArr['message'] ='登录成功';

                $returnArr['user'] = $username;

                $agent  = $_SERVER['HTTP_USER_AGENT'];

                //token的生成方式--可以使用其他方式，目前暂时使用简单的加密
                $token = md5($username.$password.$agent);//可以继续复杂化

                setcookie('username', $username,-1,'/MessageByCI/');

                setcookie('token', $token,-1,'/MessageByCI/');

              //  $_SESSION['user'] = $username;

			 }
		}

		if(!isset($returnArr['success'])){

			$returnArr['success'] =0;

            $returnArr['message'] ='用户名/密码错误';
		}

		echo json_encode($returnArr);
	}


}