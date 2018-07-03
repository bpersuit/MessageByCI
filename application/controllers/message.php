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
		if(!preg_match("/^[0-9]+$/",$userid)){

			$this->load->view('error');

			//exit();
			return;
		}

 		//采用先读取留言数据，然后在获取数据的创建者的基本信息

 		$starttime = explode(' ',microtime());

 		$MessageList = $this->Message_model->getMessageByUserId($userid);

 		foreach ($MessageList as &$messageItem) {

 			$messageUser = $this->User_model->getByUserId($messageItem['userid']);

 			$messageItem['username'] = $messageUser[0]['username'];
 			
 		}

 		$data['messageList'] = $MessageList;


		$user = $this->User_model->getByUserId($userid);

		$data['user'] = $user[0];

		$data['loginuser'] = '';

		// if(isset($_SESSION['user'])){

		//  	$data['loginuser'] = $_SESSION['user'];
		//  }

		$loginUser = $this->User_model->getCookieUser();

		if($user != null){

			$data['loginuser'] = $loginUser;
		}

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


		//对于删除操作的权限进行基本的验证 ---更换为cookie验证

		// if(!isset($_SESSION['user'])){

		// 	$this->load->view('login_message');

		// 	exit();
		// }

		$loginUser = $this->User_model->getCookieUser();

		if($loginUser == null){

			$this->load->view('login_message');

			exit();
		}

		//$loginUser = $_SESSION['user'];

		$user = $this->User_model->getByUserName('user',$loginUser);

		$returnArr=array();

		if($user[0]['id'] == $Message[0]['userid']){

			//将删除数据标识位设置为1
			$this->Message_model->updateMessage($messageid);

			$returnArr['message'] = 1;

		}else
			
			$returnArr['message'] = 0;

		echo json_encode($returnArr);

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
		// if(!isset($_SESSION['user'])){

		// 	$this->load->view('login_message');

		// 	exit();
		// }

		// $loginUser = $_SESSION['user'];

		$loginUser = $this->User_model->getCookieUser();

		if($loginUser == null){

			$this->load->view('login_message');

			exit();
		}

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

       $returnArr=array();

       $returnArr['message'] = 1;

       echo json_encode($returnArr);



	}



}