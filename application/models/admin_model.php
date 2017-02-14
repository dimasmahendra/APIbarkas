<?php
class admin_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}
	
	function getAdmin_list()
    {        
        $this->db->select('user.id, user.barkas_id, barkas.tipebarkas_id, user.email, user.level, barkas.nama, barkas.telepon');
        $this->db->from('user');
        $this->db->join('barkas', 'user.barkas_id = barkas.id', 'left'); 
        $query = $this->db->get();
        $result  = $query->result_array();
        return $result;    
    }
}
?>