<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
	 /**
	 	user数据库表的一些基本操作
	 */
	class Message_model extends CI_Model {
	 
		public function __construct(){
		 
		        parent::__construct();
		 
		        $this->load->database();
		 
		}


		/**
			获取所有未删除的数据--使用多表查询
		*/
		public function get_all(){
	 
	        $this->db->select('m.id,m.userid,m.content,m.createdate,u.username')->where('m.hasdel', 0)->from('message m')->join('user u','u.id=m.userid')->order_by("m.createdate","DESC");
	 
	        $query = $this->db->get();
	 
	        $query = $query->result_array();
	 
	        return $query;
		}


		/**
		获取某个用户的消息所有数据
		*/
		public function getByuserId($userid){

			$this->db->select('m.id,m.content,m.createdate,m.userid,u.username')->where('m.hasdel',0)->where('m.receiveid',$userid)->from('message m')->join('user u','u.id = m.userid')->order_by("m.createdate","DESC");

			$query = $this->db->get();
	 
	        $query = $query->result_array();
	 
	        return $query;
		}

		/**
			通过id值获取一条message数据
		*/

		public function getMessageById($messageid){

			$this->db->select('*')->where('id',$messageid)->from('message');

			$query = $this->db->get();

			$query = $query->result_array();

			return $query;


		}

		/*删除message表中的数据*/

		public function updateMessage($messageid){

			$data = array(

               'hasdel' => 1
            );

            $this ->db->where("id",$messageid);

            $this->db->update('message', $data);

		}
		/*增加一条数据,其中传递过来的data包含了所有数据的数组*/
		public function insertMessage($data){


			$this->db->insert('message', $data); 

		}

	}

?>