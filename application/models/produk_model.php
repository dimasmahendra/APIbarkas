<?php
class produk_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}

	function getProduk_list()
    {        
        $this->db->select('a.id, a.penitip_id, a.kategori_id, a.nama, a.berat, a.hargajual, b.nama as namapenitip');
        $this->db->from('produk a');
        $this->db->join('penitip b', 'a.penitip_id = b.id', 'left');
        $query = $this->db->get();              
        $result  = $query->result_array();
        return $result;    
    }

    function insertProduk($insertProduk)
    {        
        $this->db->trans_start();
        $this->db->insert('produk', $insertProduk);
        $this->db->trans_complete();
        $query = $this->db->insert_id();  
        //print_r($query);die();    
        if ($query == 0) {
            return true;
        } else {
            return false;
        }       
    }

    function getProdukbyId($id_produk)
    {        
        $condition = "id =" . "'" . $id_produk . "'";
        $this->db->select('*');
        $this->db->from('produk');
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

    function updatePenitip($id, $detilPenitip)
    {        
        $this->db->where('id', $id);
        $update = $this->db->update('penitip', $detilPenitip);
        if ($update == '1') 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }

    function hapusPenitip($id)
    {        
        $detilPenitip = array(
            'status'   => 'tidak aktif'
        );
        $this->db->where('id', $id);
        $delete = $this->db->update('penitip', $detilPenitip);
        if ($delete == '1') 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
}
?>