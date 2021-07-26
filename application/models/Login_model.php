<?php
class Login_model extends CI_Model
{
    public function login_user($email,$password)
    {
    	$this->db->where('email',$email)->or_where('user_name',$email);
    	$this->db->where('password',$password);
    	return $this->db->get('users')->row_array();
    }

    public function forgot_email($email)
    {
    	$this->db->where('email',$email);
    	return $this->db->get('users')->row();
    }
    public function updatePassword($id,$data)
    {
  		$this->db->where('verify_id',$id);
    	return $this->db->update('users',$data);
    }
    public function create_veritycode($email,$data)
    {
  		$this->db->where('email',$email);
    	return $this->db->update('users',$data);
    }
    public function remove_veritycode($id,$data)
    {
  		$this->db->where('verify_id',$id);
    	return $this->db->update('users',$data);
    }
    public function confirm_userdata($id)
    {
    	$this->db->where('verify_id',$id);
    	return $this->db->get('users')->row();
    }
}