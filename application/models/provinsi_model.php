<?php
class provinsi_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}
	
	function getProvinsi_list()
    {        
        $this->db->select('*');
        $query = $this->db->get('provinsi'); 
        $result  = $query->result_array();
        //print_r($result);die();
        return $result;    
    }

    function getKabupatenKota_list($provinsi_id)
    {        
        $condition = "provinsi_id = " . "'" . $provinsi_id . "'";
        $this->db->select('*');       
        $this->db->where($condition);        
        $query = $this->db->get('kabupatenkota');
        $result  = $query->result_array();   
        //print_r($result);die();
        return $result;    
    }

    function getKecamatan_list($kabupatenkota_id)
    {        
        $condition = "kabupatenkota_id = " . "'" . $kabupatenkota_id . "'";
        $this->db->select('*');       
        $this->db->where($condition);        
        $query = $this->db->get('kecamatan');
        $result  = $query->result_array();   
        //print_r($result);die();
        return $result;    
    }

    function getKelurahan_list($kecamatan_id)
    {        
        $condition = "kecamatan_id = " . "'" . $kecamatan_id . "'";
        $this->db->select('*');       
        $this->db->where($condition);        
        $query = $this->db->get('kelurahan');
        $result  = $query->result_array();   
        //print_r($result);die();
        return $result;    
    }
}
?>