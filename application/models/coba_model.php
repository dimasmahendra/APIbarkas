<?php
class coba_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}	

    public function getProduk_post()
    {
        $this->load->model('coba_model');
        $session_data = array(
            'session_key' => $this->post('session_key')                          
        );

        $session_key = $this->checkIfSessionBarkasExpired($session_data['session_key']);
        //print_r($session_key);die();
        if ($session_key == true) {

            $kategori = $this->coba_model->kategori();            
            $hasil = array();
            $a = 0;
            foreach($kategori as $index_utama => $isi_array){
                $idkat[] = $isi_array['id'];
                foreach($idkat as $key => $value){
                    $hasil[$key]['produk'] = $this->coba_model->getProdukbyId($value);
                    $kategori[$key]['produk'] = $hasil[$key]['produk'];
                }
            }
            print_r($kategori);die();           
            //$getProduk_list = $this->produk_model->getProduk_list();      
            if(!is_null($getProduk_list)){
                $hasil = array('status' => 1, 'message' => 'Produk Ditemukan', 'data' => $getProduk_list);            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Produk Tidak Ditemukan');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }                            
        }
        else {
            $hasil = array('status' => 0, 'message' => 'Session Key Tidak Ditemukan');            
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }
    }
    
    function kategori()
    {        
        $this->db->select('id, nama');
        $this->db->from('kategori');
        $query = $this->db->get();        
        $result  = $query->result_array();
        return $result;    
    }

    function produk()
    {        
        $this->db->select('*');
        $this->db->from('produk');
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

    function getProdukbyId($id_kat)
    {       
        $condition = "kategori_id =" . "'" . $id_kat . "'";
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->where($condition);
        $query = $this->db->get();               
        $result  = $query->result_array();
        //print_r($result);die();
        return $result;
    }
}?>