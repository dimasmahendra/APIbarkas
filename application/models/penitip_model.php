<?php
class penitip_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}
	
	function getPenitip_list()
    {        
        $condition = "status = 'aktif'";
        $this->db->select('*');
        $this->db->from('penitip');
        $this->db->where($condition);
        $query = $this->db->get();        
        $result  = $query->result_array();
        return $result;    
    }

    function insertPenitip($insertPenitip)
    {        
        $rekening = $insertPenitip['rekening'];
        $sql = "SELECT * FROM penitip WHERE rekening REGEXP $rekening";        
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0) {
            //print_r("hahahhahhaha");die();
            $this->db->trans_start();
            $this->db->insert('penitip', $insertPenitip);
            $this->db->trans_complete();
            $query = $this->db->insert_id();      
            if ($query == 0) {
                return true;
            } else {
                return false;
            }
        } 
        else{
            return false;
        }
    }

    function getDetilPenitip_list($id_penitip)
    {        
        $condition = "id =" . "'" . $id_penitip . "'";
        $this->db->select('*');
        $this->db->from('penitip');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        
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

    function getDetailProduk_list($id_produk)
    {        
        $condition = "penitip_id =" . "'" . $id_produk . "'";
        $this->db->select('a.id, a.penitip_id, a.kategori_id, a.nama, a.berat, a.hargajual, c.nama as namakategori');
        $this->db->from('produk a');
        $this->db->join('kategori c', 'a.kategori_id = c.id');
        $this->db->where($condition);
        $query = $this->db->get();
        $result  = $query->result_array();
        return $result;
    }
}
?>