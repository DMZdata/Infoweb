<?php
class Posts_model extends CI_Model
{
    function allposts_count()
    {   
        $query = $this->db->get('posts');
        return $query->num_rows();  
    }

    function heartbeat($post_id,$ip_addr)
    {   
        $num_rows = $this->db->where(['post_id'=>$post_id,'ip_addr'=>$ip_addr])->get('online')->num_rows();
        if($num_rows>0)
        {
            $this->db->where(['post_id'=>$post_id,'ip_addr'=>$ip_addr]);
            return $this->db->update('online',['updated'=>date('Y-m-d H:i:s')]); 
        }
        else
        {
            return $this->db->insert('online',['post_id'=>$post_id,'ip_addr'=>$ip_addr]); 
        }
        
    }

    function userposts($limit,$start,$col,$dir,$user_id)
    {   
       $query = $this->db->where('user_id',$user_id)->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('posts');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function allposts($limit,$start,$col,$dir)
    {   
       // $query = $this->db->limit($limit,$start)
       //          ->order_by($col,$dir)
       //          ->get('posts');
        $query = $this->db->limit($limit,$start)
                ->order_by("id","DESC")
                ->get('posts');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function posts_search($limit,$start,$search,$col,$dir)
    {
        $query = $this->db->like('title',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('posts');
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function user_posts_search($limit,$start,$search,$col,$dir,$user_id)
    {
        $query = $this->db->where('user_id',$user_id)->like('title',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('posts');
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function posts_search_count($search)
    {
        $query = $this->db->like('title',$search)
                ->get('posts');
    
        return $query->num_rows();
    }

    function user_posts_search_count($search,$user_id)
    {
        $query = $this->db->where('user_id',$user_id)->like('title',$search)
                ->get('posts');
    
        return $query->num_rows();
    }

    //Store Posts
    function store_post($data)
    {
        $this->db->insert('posts',$data);
        return $this->db->insert_id();
    } 

    //Posts Delete
    function posts_delete($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('posts');
    }
    
    //Get Post Data
    function posts_data($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('posts')->row();
    }
    //Get Media Data
    function post_data($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('posts')->result();
    }
    //Get Post Data
    function posts_count($id)
    {
        $this->db->where('user_id',$id);
        return $this->db->get('posts')->num_rows();
    }
    
    //Update Posts data
    function update_posts($data,$id)
    {
        $this->db->where('id',$id);
        return $this->db->update('posts',$data);
    }

    //Get All Post Data
    function get_allposts_data()
    {
        $userdata = $this->session->userdata('user');
        if ($userdata['user_role'] == '0') {    
            return $this->db->where('user_id',$userdata['id'])->get('posts')->result();
        }
        return $this->db->get('posts')->result();
    }

}