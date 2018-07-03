<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
 	user数据库表的一些基本操作
 */
class User_model extends CI_Model {
 
	public function __construct(){
	 
	        parent::__construct();
	 
	        $this->load->database();
	 
	    }


	public function insert($arr,$table){
	 
		$this->db->insert($table, $arr);
	 
	    if ($this->db->affected_rows() > 0){
	 
	        return $this->db->insert_id();
	 
	    }else{
	 
	        return FALSE;
	 
	    }
	 
	}
	/**
	获取所有数据
	*/
	public function get_all($table){
 
        $this->db->select('*');
 
        $query = $this->db->get($table);
 
        $query = $query->result_array();
 
        return $query;
 
    }
    /**
	*获取一条记录
    */
    public function getByUserName($table, $username){

    	 $this->db->select('*');

    	 $this->db->where_in('username',$username);

    	 $query = $this->db->get($table);
 
         $query = $query->result_array();
 
         return $query;
    }

    /**
    	通过用户id获取用户的基本信息
    */
 	public function getByUserId($userid){

 		$this->db->select('id,username')->where('id',$userid)->from('user');

 		$query = $this->db->get();
 
        $query = $query->result_array();
 
        return $query;
 	}

 	/**
	插入一条数据记录
 	*/

 	public function insertUser($data){

 		$this->db->insert('user', $data); 
 	}

 	/*使用cookie验证,通过获取的cookie中的用户名以及token来进行验证*/
 	public function getCookieUser(){

 		if(isset($_COOKIE['username']) && isset($_COOKIE['token'])){

 			$username = trim($_COOKIE['username']);

 			$token = trim($_COOKIE['token']);

  			$agent  = $_SERVER['HTTP_USER_AGENT'];

  			if($username != null && preg_match("/^[a-zA-Z]+$/",$username)){

				$user = $this->getByUserName('user',$username);

				if($user != null){

					$mdToken = md5($user[0]['username'].$user[0]['password'].$agent);

					if($mdToken == $token){

						return $user[0]['username'];

					}

				}
				
			}

 		}

 		setcookie('username', NULL,-1,'/MessageByCI/');

		setcookie('token', NULL,-1,'/MessageByCI/');

 		return null;

  	}
 
}