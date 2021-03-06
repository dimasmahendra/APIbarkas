<?php
class transaksi_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}

	function getProdukTransaksi_List($kodebarang)
    {        
        $condition = "unikid =" . "'" . $kodebarang . "'";
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

    function updateProduk($id, $detilProduk)
    {        
        $this->db->where('id', $id);
        $update = $this->db->update('produk', $detilProduk);
        if ($update == '1') 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }

    function hapusProduk($id)
    {        
        $statusProduk = array(
            'status'   => 'tidak aktif'
        );
        $this->db->where('id', $id);
        $delete = $this->db->update('produk', $statusProduk);
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