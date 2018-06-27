<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class User_model extends CI_Model {
 
	public function __construct()
	 
	    {
	 
	        parent::__construct();
	 
	        $this->load->database();
	 
	    }
	 
	public function insert($arr,$table){
	 
		$this->db->insert($table, $arr);
	 
	        if ($this->db->affected_rows() > 0)
	 
	        {
	 
	            return $this->db->insert_id();
	 
	        }
	 
	        else
	 
	        {
	 
	            return FALSE;
	 
	        }
	 
	}
	public function get_all($table)
 
    {
 
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
 
 
}