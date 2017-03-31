<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// http://www.tuicool.com/articles/7z6nem  数据库操作

class hello_model extends CI_Model { 

	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	//获取
	public function getAllData(){
		$data=$this->db->get('Student')->result();   
		return $data; 

		// $sql = "SELECT * FROM Student WHERE username = 1 AND sex = 'male'";
		// //多结果标准查询
		// $query = $this->db->query($sql)->result(); //自定义
		// return $query;

	}

	//添加
	//	$this->db->insert('archive',$archive); 返回bool值
	//	$insert_id = $this->db->insert_id();
	//	$this->db->insert_batch('archive',$data);  //插入多条
	public function InsertData($username, $sex, $age, $height){
		$data = array('username' => $username, 'sex' => $sex, 
		'age' => $age, 'height' => $height);
		$str = $this->db->insert('Student', $data);
		return $str;
	}

	//修改
	public function UpdateData(){
		$sql = "UPDATE Student SET age='133' WHERE username = 1 AND sex = 'male'";
		//多结果标准查询
		$query = $this->db->query($sql); //自定义 返回bool
		return $query;
	}

	//删除
	public function DeleteData(){
		$sql = "DELETE FROM Student WHERE username = 1";
		$query = $this->db->query($sql); //自定义 返回bool
		return $query;
	}
}
