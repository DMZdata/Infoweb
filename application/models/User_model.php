<?php
class User_model extends CI_Model
{
    function alluser_count()
    {   
        $query = $this->db->get('users');
        return $query->num_rows();  
    }

    function allusers($limit,$start,$col,$dir)
    {   
       $query = $this->db->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('users');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function user_search($limit,$start,$search,$col,$dir)
    {
        $query = $this->db->like('id',$search)
                ->or_like('user_name',$search)
                ->or_like('email',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('users');
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function user_search_count($search)
    {
        $query = $this->db->like('id',$search)
                ->or_like('user_name',$search)
                ->or_like('email',$search)
                ->get('users');
    
        return $query->num_rows();
    } 

    function user_delete($id)
    {
    	$this->db->where('id',$id);
    	$this->db->delete('users');
    }

    function user_data($id)
    {
    	$this->db->where('id',$id);
    	return $this->db->get('users')->row();
    }

    function updateUser($data,$id){
    	$this->db->where('id',$id);
    	return $this->db->update('users',$data);
    }

}