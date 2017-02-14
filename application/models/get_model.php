<?php
class get_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}
	
	function penitip()
    {        
        $condition = "status = 'aktif'";
        $this->db->select('id, nama');
        $this->db->from('penitip');
        $this->db->where($condition);
        $query = $this->db->get();        
        $result  = $query->result_array();
        return $result;    
    }

    function kategori()
    {        
        $this->db->select('id, nama');
        $this->db->from('kategori');
        $query = $this->db->get();        
        $result  = $query->result_array();
        return $result;    
    }

    function kategoribyid($id_kategori)
    {        
        $condition = "id =" . "'" . $id_kategori . "'";
        $this->db->select('*');
        $this->db->from('kategori');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        //print_r($query->row());die();        
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }  
    }
}?>