<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
 
class Apiv2 extends REST_Controller {

    function __construct()
    {
        parent::__construct();
    }
    /*--------------------------------------------------- Get Penitip Barkas  --------------------------------------------------- */
    public function getPenitip_get()
    {
        $this->load->model('get_model');
        $get = $this->get_model->penitip();
        if(!is_null($get)){
            $hasil = array('status' => 1, 'message' => 'Penitip Ditemukan', 'data' => $get);            
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }  
        else {
            $hasil = array('status' => 0, 'message' => 'Penitip Tidak Ditemukan');            
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }                           
        
    }  
    /*--------------------------------------------------- Get Penitip Barkas  --------------------------------------------------- */

    /*--------------------------------------------------- Get Kategori Barkas  --------------------------------------------------- */
    public function getKategori_get()
    {
        $this->load->model('get_model');
        $get = $this->get_model->kategori();
        //print_r($get);die();
        if(!is_null($get)){
            $hasil = array('status' => 1, 'message' => 'Penitip Ditemukan', 'data' => $get);            
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }  
        else {
            $hasil = array('status' => 0, 'message' => 'Penitip Tidak Ditemukan');            
            header('Content-Type: application/json');
            echo json_encode($hasil);
        }                           
        
    }  
    /*--------------------------------------------------- Get Kategori Barkas  --------------------------------------------------- */
}