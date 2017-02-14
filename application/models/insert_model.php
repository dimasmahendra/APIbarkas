<?php
class insert_model extends CI_Model{

	function __construct() {
		parent::__construct();
	}
	
	function form_insert($dataMerchand){
		
		$this->db->trans_start();
        $this->db->insert('t_merchant', $dataInfo);
        $this->db->trans_complete();
        $query = $this->db->insert_id();
                
        if ($query == 0) {
            return true;
        } else {
            return false;
        }
	}

	function read_user_information($id_user)
    {        
        $condition = "id_user =" . "'" . $id_user . "'";
        $this->db->select('*');
        $this->db->from('t_user_admin');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function match_information($username)
    {        
        $condition = "username =" . "'" . $username . "'";
        $this->db->select('*');
        $this->db->from('t_user_admin');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    function get_list()
    {        
        $this->db->select('*'); 
        $query = $this->db->get('t_user_admin'); 
        $result  = $query->result_array();
        
        return $result;
    }

    function get_list_merchand($id)
    {        
        $condition = "id_user = " . "'" . $id . "'";
        $this->db->select('*');       
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get('t_user_admin');
        $result  = $query->result_array();        
        return $result;   
    }

    function get_Profile($id_user)
    {        
        $condition = "id_user = " . "'" . $id_user . "'";
        $this->db->select('username, email, password');       
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get('t_user_admin');
        $result  = $query->result_array();
               
        return $result;   
    }

    function update_Profile($id, $pass, $dataProfile)
    {
        $condition = "id_user = " . "'" . $id . "'" . " AND " . "password = " . "'" . $pass . "'"; 
        $this->db->select('*');       
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get('t_user_admin'); 
        $hasil = $query->num_rows();        

        if ($hasil == '1') 
        {
            $this->db->where('id_user', $id);
            $update = $this->db->update('t_user_admin', $dataProfile);           

            if ($update == '1') 
            {
                return TRUE;
            } 
            else 
            {
                return FALSE;
            }
        } 
        else 
        {
            return false;
        }       
    } 

    function updateUserProfile($id, $dataProfile)
    {
        $this->db->where('id_user', $id);
        $update = $this->db->update('t_user_admin', $dataProfile);           

        if ($update == '1') 
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }     
    } 

    
    
    function update_merchand($id, $dataMerchand)
    {
        $this->db->where('id_user', $id);
        $update = $this->db->update('t_user_admin', $dataMerchand);
        if ($update) {
            return TRUE;
        } 
        else {
            return FALSE;
        }
    }

    function delete($table, $id_user) {
        $this->db->where('id_user' , $id_user);
        $query = $this->db->delete($table);
        if ($query) {
            return TRUE;
        } 
        else {
            return FALSE;
        }
    }
}
?>