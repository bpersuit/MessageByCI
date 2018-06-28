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
 
}