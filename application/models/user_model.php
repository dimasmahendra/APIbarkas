<?php
class user_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }	
	
	function getSessionKey($adminbarkas_id)
    {
		$condition = "user_id =" . "'" . $adminbarkas_id . "'";        
        $this->db->select('*');
        $this->db->from('sessionbarkas');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();        
        if ($query->num_rows() == 1)
        {
            $session_key = $query->row('session_key');
            return $session_key;            
        } 
        else 
        {
            return false;
        }
	}

	function cekSessionKeyExist($session_key)
    {
		$condition = "session_key =" . "'" . $session_key . "'";        
        $this->db->select('*');
        $this->db->from('sessionbarkas');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();   
        //print_r($query);die();     
        if ($query->num_rows() == 1)
        {
            return true;            
        } 
        else 
        {
            return false;
        }
	}

	function upadateSessionKey($session, $expired, $status)
    {
		$loginData = array(
            'status'     => $status,              
            'expired_at'  => $expired               
        );
		
		$this->db->where('session_key', $session);
        $update = $this->db->update('sessionbarkas', $loginData);
        //print_r($update);die();
        if ($update == '1') 
        {
            
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
	}

	function insertSessionKey($session_key, $expired, $status)
    {
		$loginData = array(
            'status'     => $status,              
            'expired_at'  => $expired,              
            'session_key'  => $session_key               
        );
		
		$this->db->trans_start();
        $this->db->insert('sessionbarkas', $loginData);
        $this->db->trans_complete();
        $query = $this->db->insert_id();
        //print_r($query);die();     
        if ($query == 0) {
            return true;
        } else {
            return false;
        }		
	}	
}