<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
 
class Apiv1 extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model(array('login_model','user_model','admin_model'));
        $this->load->helper('string');
    }

    private function generateRandomString($length = 4) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function createOrUpdateSessionBarkas($adminbarkas_id = null)
    {
        $session_key = null;
        $status = 1;
        $newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +30 minutes"));

        if (!is_null($adminbarkas_id)) 
        {            
            $expired = $newTime;            
            $session = $this->user_model->getSessionKey($adminbarkas_id); 
            
            if (empty($session)) 
            {
                $session_key = random_string('alpha', 16);
                $session = $this->user_model->insertSessionKey($session_key, $expired, $status); 
                if ($session == TRUE) {
                    return $session_key;
                }
            }
            else
            {                
                $session_key = $this->user_model->upadateSessionKey($session, $expired, $status);                 
                if ($session_key == '1') {
                    return $session;
                }                
            }
        }        
    }

    private function checkIfSessionBarkasExpired($session_key = null)
    {
        $boolean = false;
        $expired = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +30 minutes"));
        $status = 1;
        //print_r($session_key);die();
        if (!is_null($session_key)) {
            $cek_session = $this->user_model->cekSessionKeyExist($session_key);
            //print_r($cek_session);die();
            if ($cek_session == 1) {
                $session = $this->user_model->upadateSessionKey($session_key, $expired, $status); 
                if ($session == '1') {
                    $boolean = true;
                }                
            }
            else {
                return false;
            }            
        }
        return $boolean;
    }

    /*------------------------------------------------------- Login admin -------------------------------------------------------- */
    public function loginadmin_post()
    {
        $loginData = array(
            'email'     => $this->post('email'),              
            'password'  => $this->post('password'),                 
            'level'     => $this->post('level')                  
        );
        
        $result = $this->login_model->login($loginData);  
        //print_r($result);die();      
        if ($result == TRUE)
        {
            $session_key = $this->createOrUpdateSessionBarkas($result['id']);
            $hasil = array('status' => 1, 'message' => 'Admin Barkas Ditemukan', 'session_key' => $session_key, 'data' => $result);
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }
        else {
            $hasil = array('status' => 0, 'message' => 'Akun tidak ada');            
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }
    }
    /*------------------------------------------------------- Login admin -------------------------------------------------------- */

    /*------------------------------------------------------ Logout admin -------------------------------------------------------- */
    public function logout_post()
    {
        $logoutData = array(
            'session_key' => $this->post('session_key')                          
        );

        $session_key = $this->checkIfSessionBarkasExpired($logoutData['session_key']);
        if ($session_key == true) {
            $updateStatus = $this->login_model->logout($logoutData['session_key']); 
            if ($updateStatus == 1) {
                $hasil = array('status' => 1, 'message' => 'Logout Berhasil');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }            
        }
        else {
            $hasil = array('status' => 0, 'message' => 'Login Habis');            
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }
    }    
    /*------------------------------------------------------ Logout admin ------------------------------------------------------- */

    /*------------------------------------------------------ Admin Barkas ------------------------------------------------------- */
    public function getAdmin_post()
    {
        $logoutData = array(
            'session_key' => $this->post('session_key')                          
        );

        $session_key = $this->checkIfSessionBarkasExpired($logoutData['session_key']);
        //print_r($session_key);die();
        if ($session_key == true) {
            $getAdminList = $this->admin_model->getAdmin_list();
            if(!is_null($getAdminList)){
                $hasil = array('status' => 1, 'message' => 'Admin Barkas Ditemukan', 'data' => $getAdminList);            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Admin Barkas Tidak Ada');            
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
    /*------------------------------------------------------ Admin Barkas ------------------------------------------------------- */

    /*------------------------------------------------------ Penitip Barkas ----------------------------------------------------- */
    public function getPenitip_post()
    {
        $this->load->model('penitip_model');
        $logoutData = array(
            'session_key' => $this->post('session_key')                          
        );

        $session_key = $this->checkIfSessionBarkasExpired($logoutData['session_key']);
        //print_r($session_key);die();
        if ($session_key == true) {
            $getPenitipList = $this->penitip_model->getPenitip_list();
            //print_r($getPenitipList);die();
            if(!is_null($getPenitipList)){
                $hasil = array('status' => 1, 'message' => 'Penitip Ditemukan', 'data' => $getPenitipList);            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Penitip Tidak Ditemukan');            
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

    public function insertpenitipbarkas_post()
    {
        $this->load->model('penitip_model');
        $session_data = array(
            'session_key' => $this->post('session_key')                          
        );
        $session_key = $this->checkIfSessionBarkasExpired($session_data['session_key']);
        if ($session_key == true) {
            $insertPenitip = array(
                'nama'              => $this->post('nama'),
                'noktp'             => $this->post('noktp'),
                'rekening'          => $this->post('rekening'),
                'jeniskelamin'      => $this->post('jenis_kelamin'),
                'tempatlahir'       => $this->post('tempatlahir'),
                'tanggallahir'      => $this->post('tanggallahir'),
                'alamatktp'         => $this->post('alamatktp'),              
                'alamatsekarang'    => $this->post('alamatsekarang'),               
                'telepon'           => $this->post('telepon'),               
                'email'             => $this->post('email'),               
                'pekerjaan'         => $this->post('pekerjaan'),                 
                'status'            => $this->post('status')                 
            );
            $insertPenitip = $this->penitip_model->insertPenitip($insertPenitip);
            //print_r($insertPenitip);die();
            if($insertPenitip == true){
                $hasil = array('status' => 1, 'message' => 'Insert Data Berhasil');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Rekening Sama');            
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

    public function getDetilPenitip_post()
    {
        $this->load->model('penitip_model');
        $detilPenitip = array(
            'session_key'   => $this->post('session_key'),                       
            'id'            => $this->post('penitip_id')                         
        );

        $session_key = $this->checkIfSessionBarkasExpired($detilPenitip['session_key']);
        //print_r($session_key);die();
        if ($session_key == true) {
            //print_r($detilPenitip['id']);die();
            $getDetilPenitipList = $this->penitip_model->getDetilPenitip_list($detilPenitip['id']);
            //print_r($getPenitipList);die();
            if(!is_null($getDetilPenitipList)){
                $hasil = array('status' => 1, 'message' => 'Penitip Ditemukan', 'data' => $getDetilPenitipList);            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Penitip Tidak Ditemukan');            
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

    public function updatePenitipBarkas_post()
    {
        $this->load->model('penitip_model');
        $session_data = $this->post('session_key');
        $id = $this->post('id');
        $detilPenitip = array(            
            'nama'              => $this->post('nama'),
            'noktp'             => $this->post('noktp'),
            'rekening'          => $this->post('rekening'),
            'jeniskelamin'      => $this->post('jeniskelamin'),
            'tempatlahir'       => $this->post('tempatlahir'),
            'tanggallahir'      => $this->post('tanggallahir'),
            'alamatktp'         => $this->post('alamatktp'),              
            'alamatsekarang'    => $this->post('alamatsekarang'),               
            'telepon'           => $this->post('telepon'),               
            'email'             => $this->post('email'),               
            'pekerjaan'         => $this->post('pekerjaan'),
            'status'            => $this->post('status')                           
        );
        //print_r($id);die();
        $session_key = $this->checkIfSessionBarkasExpired($session_data);
        //print_r($session_key);die();
        if ($session_key == true) {
            //print_r($detilPenitip['id']);die();
            $update = $this->penitip_model->updatePenitip($id, $detilPenitip);
            //print_r($getPenitipList);die();
            if($update == TRUE){
                $hasil = array('status' => 1, 'message' => 'Update Data Sukses');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Update Data Gagal');            
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

    public function deletePenitipBarkas_post()
    {
        $this->load->model('penitip_model');
        $session_data = $this->post('session_key');
        $id = $this->post('id');
        $session_key = $this->checkIfSessionBarkasExpired($session_data);
        //print_r($session_key);die();
        if ($session_key == true) {
            //print_r($detilPenitip['id']);die();
            $delete = $this->penitip_model->hapusPenitip($id);
            //print_r($getPenitipList);die();
            if($delete == TRUE){
                $hasil = array('status' => 1, 'message' => 'Sukses');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Gagal');            
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

    public function getDetilProduk_post()
    {
        $this->load->model('penitip_model');
        $detilProduk = array(
            'session_key'   => $this->post('session_key'),                       
            'id'            => $this->post('produk_id')                         
        );

        $session_key = $this->checkIfSessionBarkasExpired($detilProduk['session_key']);
        //print_r($session_key);die();
        if ($session_key == true) {
            //print_r($detilPenitip['id']);die();
            $getDetilProdukList = $this->penitip_model->getDetailProduk_list($detilProduk['id']);
            $getDetilPenitipList = $this->penitip_model->getDetilPenitip_list($detilProduk['id']);
            //print_r($getDetilPenitipList);die();
            if(!is_null($getDetilProdukList)){
                $hasil = array('status' => 1, 'message' => 'Penitip Ditemukan', 'data' => $getDetilPenitipList, 'data2' => $getDetilProdukList);            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Penitip Tidak Ditemukan');            
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
    /*------------------------------------------------------ Penitip Barkas ----------------------------------------------------- */

    /*------------------------------------------------------ Produk Barkas ----------------------------------------------------- */
    public function getProduk_post()
    {
        $this->load->model('produk_model');
        $session_data = array(
            'session_key' => $this->post('session_key')                          
        );

        $session_key = $this->checkIfSessionBarkasExpired($session_data['session_key']);
        //print_r($session_key);die();
        if ($session_key == true) {
            $getProduk_list = $this->produk_model->getProduk_list();
            //print_r($getProduk_list);die();
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

    public function insertprodukbarkas_post()
    {
        $this->load->model('produk_model');
        $lokasi_id = $this->post('lokasi_id'); 
        $kode = date('mdy') . $this->generateRandomString();
        if ($lokasi_id == '1') {
            $idunik = 'CGK'.$kode;
        }
        else if ($lokasi_id == '2') {
            $idunik = 'JOG'.$kode;
        }
        else if ($lokasi_id == '3') {
            $idunik = 'SRG'.$kode;
        }
        
        $session_data = array(
            'session_key' => $this->post('session_key')                          
        );
        $session_key = $this->checkIfSessionBarkasExpired($session_data['session_key']);
        if ($session_key == true) {
            $insertProduk = array(
                'penitip_id'   => $this->post('penitip_id'),
                'kategori_id'  => $this->post('kategori_id'),
                'nama'         => $this->post('nama'),
                'berat'        => $this->post('berat'),
                'hargajual'    => $this->post('hargajual'),                
                'status'       => $this->post('status'),              
                'unikid'       => $idunik                
            );

            $insert = $this->produk_model->insertProduk($insertProduk);
            //print_r($insertPenitip);die();
            if($insert == true){
                $hasil = array('status' => 1, 'message' => 'Insert Data Berhasil');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Rekening Sama');            
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

    public function getprodukbyid_post()
    {
        $this->load->model(array('penitip_model', 'produk_model','get_model'));
        $detilProduk = array(
            'session_key'   => $this->post('session_key'),                       
            'id'            => $this->post('produk_id')                         
        );

        $session_key = $this->checkIfSessionBarkasExpired($detilProduk['session_key']);
        //print_r($session_key);die();
        if ($session_key == true) {
            //print_r($detilPenitip['id']);die();
            $produk = array($this->produk_model->getProdukbyId($detilProduk['id']));
            $penitip = array($this->penitip_model->getDetilPenitip_list($produk[0]->penitip_id));  
            $kategori = array($this->get_model->kategoribyid($produk[0]->kategori_id));  
            //print_r($kategori);die();
            $hasil = array();
            foreach($produk as $index_utama => $isi_array){
                foreach($isi_array as $key => $value){
                    $hasil[$key] = $value;
                }
            }
            $hasil['penitip_id'] = $penitip[0];
            $hasil['kategori_id'] = $kategori[0];

            if(!is_null($hasil)){
                $result = array('status' => 1, 'message' => 'Produk Ditemukan', 'data' => $hasil);            
                header('Content-Type: application/json');
                echo json_encode($result);
            }  
            else {
                $result = array('status' => 0, 'message' => 'Produk Tidak di temukan');            
                header('Content-Type: application/json');
                echo json_encode($result);
            }                           
        }
        else {
            $result = array('status' => 0, 'message' => 'Session Key Tidak Ditemukan');            
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function updateprodukbarkas_post()
    {
        $this->load->model('produk_model');
        $session_data = $this->post('session_key');
        $id = $this->post('id');

        $detilProduk = array(            
            'nama'          => $this->post('nama'),
            'penitip_id'    => $this->post('penitip_id'),
            'kategori_id'   => $this->post('kategori_id'),
            'nama'          => $this->post('nama'),
            'berat'         => $this->post('berat'),
            'hargajual'     => $this->post('hargajual'),
            'status'        => $this->post('status')                          
        );       
        //print_r($detilProduk);die(); 
        $session_key = $this->checkIfSessionBarkasExpired($session_data);        
        if ($session_key == true) {
            $update = $this->produk_model->updateProduk($id, $detilProduk);
            //print_r($update);die(); 
            if($update == TRUE){
                $hasil = array('status' => 1, 'message' => 'Update Data Sukses');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Update Data Gagal');            
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

    public function deleteproduk_post()
    {
        $this->load->model('produk_model');
        $session_data = $this->post('session_key');
        $id = $this->post('id');
        $session_key = $this->checkIfSessionBarkasExpired($session_data);
        if ($session_key == true) {
            $delete = $this->produk_model->hapusProduk($id);
            if($delete == TRUE){
                $hasil = array('status' => 1, 'message' => 'Sukses');            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Gagal');            
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
    /*------------------------------------------------------ Produk Barkas ----------------------------------------------------- */

    /*------------------------------------------------------ Transaksi Barkas ----------------------------------------------------*/
    public function getproduktransaksi_post()
    {
        $this->load->model('transaksi_model');
        $session_key = $this->checkIfSessionBarkasExpired($this->post('session_key'));
        if ($session_key == true) {
            $get = $this->transaksi_model->getProdukTransaksi_List($this->post('kodebarang'));            
            if(!is_null($get)){
                $hasil = array($get);            
                header('Content-Type: application/json');
                echo json_encode($hasil);
            }  
            else {
                $hasil = array('status' => 0, 'message' => 'Gagal');            
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
    /*------------------------------------------------------ Transaksi Barkas ----------------------------------------------------*/
}