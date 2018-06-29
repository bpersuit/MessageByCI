<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*主要是留言板的主要处理函数，包括显示、删除、增加等*/

class message extends CI_Controller {

	public function __construct(){
	 
		parent::__construct();
	 
		$this->load->model('Message_model');

		$this->load->model('User_model');

		$this->load->library('safefilter');
	 
	}

/*
	展示某个用户的留言板的数据信息
*/
	public function index(){

		$userid = $_GET['userid'];

		//验证userid的合法性
		if(!preg_match("/^[0-9\s]+$/",$userid)){

			$this->load->view('error');

			//exit();
			return;
		}

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

		if(!is_numeric($messageid)){

			$this->load->view('error');

			return;
		};

		$Message = $this->Message_model->getMessageById($messageid);

		if(count($Message) != 1){

			$this->load->view('error');

			return;
		}


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

		//对于输入的内容进行防注入/XSS

		$content = $this->safefilter->filter($content);

		if($content == '' || !is_numeric($receiveid)){

			$this->load->view('error');

			return;
		}

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