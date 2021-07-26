<?php
class Register_model extends CI_Model
{
    public function storeUser($data)
    {
	   $this->db->insert('users', $data);
	   return $this->db->insert_id();
    }
}