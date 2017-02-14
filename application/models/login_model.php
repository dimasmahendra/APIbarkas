<?php

Class login_model extends CI_Model
{
    public function login($loginData)
    {        
        $condition = "email =" . "'" . $loginData['email'] . "' AND " . "level =" . "'" . $loginData['level'] . "'";        
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where($condition);        
        $this->db->limit(1);
        $query = $this->db->get();    

        if ($query->num_rows() == 1) 
        {
            $result = $query->row_array();   
            //print_r($result);die();        
            if (password_verify($loginData['password'], $result['password'])) 
            {
                $data['id'] = $result['id'];
                $data['email'] = $result['email'];
                $data['level'] = $result['level'];
                $data['created_at'] = $result['created_at'];
                $data['updated_at'] = $result['updated_at'];
                return $data;
            }
            else
            {
                return false;
            }            
        } 
        else 
        {          
            return false;
        }
    }// Insert registration data in database   

    public function logout($session_key)
    {        
        $logoutData = array(
            'status'     => 0
        );
        
        $this->db->where('session_key', $session_key);
        $update = $this->db->update('sessionbarkas', $logoutData);
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
}
?>