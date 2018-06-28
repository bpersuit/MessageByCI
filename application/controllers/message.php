<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*主要是留言板的主要处理函数，包括显示、删除、增加等*/

class message extends CI_Controller {

	public function __construct(){
	 
		parent::__construct();
	 
		$this->load->model('Message_model');

		$this->load->model('User_model');
	 
	}

/*
	展示某个用户的留言板的数据信息
*/
	public function index(){

		$userid = $_GET['userid'];

		$Message = $this->Message_model->getByuserId($userid);

		$data['messageList'] = $Message;

		$user = $this->User_model->getByUserId($userid);

		$data['user'] = $user[0];

		$data['loginuser'] = '';

		if(isset($_SESSION['user'])){

		 	$data['loginuser'] = $_SESSION['user'];
		 }

		//print_r($user[0]['username']);
		//print_r(count($Message));

		$this->load->view('message',$data);
	} 


	/*
		删除留言数据的方法
	*/
	public function delete(){


		$messageid = $_POST['messageid'];

		$Message = $this->Message_model->getMessageById($messageid);


		//对于删除操作的权限进行基本的验证

		if(!isset($_SESSION['user'])){

			$this->load->view('login_message');

			exit();
		}

		$loginUser = $_SESSION['user'];

		$user = $this->User_model->getByUserName('user',$loginUser);

		if($user[0]['id'] == $Message[0]['userid']){


			//将删除数据标识位设置为1
			$this->Message_model->updateMessage($messageid);

			echo 1;

		}else
			echo 0;

	}

	/*增加留言信息*/
	public function addMessage(){

		$content = trim($_POST['inputText']);

		$receiveid = $_POST['receiveid'];

		//对于权限进行验证
		if(!isset($_SESSION['user'])){

			$this->load->view('login_message');

			exit();
		}

		$loginUser = $_SESSION['user'];

		$user = $this->User_model->getByUserName('user',$loginUser);

		$userid = $user[0]['id'];

		$currentTime =  date('Y-m-d H:i:s');


		$data = array(

               'userid' => $userid ,

               'content' => $content ,

               'createdate' => $currentTime,

               'receiveid' => $receiveid

        );

        $this->Message_model->insertMessage($data);

        echo 1;

	}



}