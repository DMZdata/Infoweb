<?php
class Album_model extends CI_Model
{
    function allalbum_count($online=false)
    {   
        if($online){
            $subquery = "SELECT o1.post_id,COALESCE(o2.online,0) online ,IFNULL(max(o2.updated), max(o1.updated)) updated FROM `online` o1 LEFT JOIN (SELECT count(1) as `online`,post_id,max(updated) updated FROM `online` where updated > (now() - INTERVAL 10 SECOND) GROUP by post_id ORDER BY updated asc) o2 ON o2.post_id = o1.post_id group by o1.post_id";
            $query = $this->db->select('a.id')
                    ->where('online > ',0)
                    ->join("($subquery)  t2","t2.post_id = a.id","left")
                    ->group_by('a.id') 
                    ->get('album a');
            return $query->num_rows();
        }
        else{
            $query = $this->db->get('album');
            return $query->num_rows();  
        }

    }

    function useralbums($limit,$start,$col,$dir,$user_id)
    {   
        $subquery = "SELECT o1.post_id,COALESCE(o2.online,0) online ,IFNULL(max(o2.updated), max(o1.updated)) updated FROM `online` o1 LEFT JOIN (SELECT count(1) as `online`,post_id,max(updated) updated FROM `online` where updated > (now() - INTERVAL 10 SECOND) GROUP by post_id ORDER BY updated asc) o2 ON o2.post_id = o1.post_id group by o1.post_id";

        $query = $this->db->select('a.*,COALESCE(t2.online,0) online,t2.updated')
                ->where('user_id',$user_id)
                ->join("($subquery)  t2","t2.post_id = a.id","left")
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->group_by('a.id') 
                ->get('album a');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function allalbums($limit,$start,$col,$dir,$online=false)
    {   
       $subquery = "SELECT o1.post_id,COALESCE(o2.online,0) online ,IFNULL(max(o2.updated), max(o1.updated)) updated FROM `online` o1 LEFT JOIN (SELECT count(1) as `online`,post_id,max(updated) updated FROM `online` where updated > (now() - INTERVAL 10 SECOND) GROUP by post_id ORDER BY updated asc) o2 ON o2.post_id = o1.post_id group by o1.post_id";

       
       if($online){
            $query = $this->db->select('a.*,COALESCE(t2.online,0) online,t2.updated')
                ->join("($subquery)  t2","t2.post_id = a.id","left")
                ->where("online > ",0)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->group_by('a.id')
                ->get('album a'); 
       }
       else{
           $query = $this->db->select('a.*,COALESCE(t2.online,0) online,t2.updated')
                    ->join("($subquery)  t2","t2.post_id = a.id","left")
                    ->limit($limit,$start)
                    ->order_by($col,$dir)
                    ->group_by('a.id')
                    ->get('album a');
       }
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function albums_search($limit,$start,$search,$col,$dir,$online = false)
    {
        $subquery = "SELECT o1.post_id,COALESCE(o2.online,0) online ,IFNULL(max(o2.updated), max(o1.updated)) updated FROM `online` o1 LEFT JOIN (SELECT count(1) as `online`,post_id,max(updated) updated FROM `online` where updated > (now() - INTERVAL 10 SECOND) GROUP by post_id ORDER BY updated asc) o2 ON o2.post_id = o1.post_id group by o1.post_id";

        if($online){
            $query = 
            $this->db->select('a.*,COALESCE(t2.online,0) online,t2.updated')
                ->join("($subquery)  t2","t2.post_id = a.id","left")
                ->like('a.title',$search)
                ->where('online>',0)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->group_by('a.id')
                ->get('album a');
        }
        else{
            $query = 
                $this->db->select('a.*,COALESCE(t2.online,0) online,t2.updated')
                    ->join("($subquery)  t2","t2.post_id = a.id","left")
                    ->like('a.title',$search)
                    ->limit($limit,$start)
                    ->order_by($col,$dir)
                    ->group_by('a.id')
                    ->get('album a');
        }
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    //Get Post Data
    function post_count($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('posts')->num_rows();
    }

    function albums_search_count($search,$online=false)
    {
        if($online){
            $subquery = "SELECT o1.post_id,COALESCE(o2.online,0) online ,IFNULL(max(o2.updated), max(o1.updated)) updated FROM `online` o1 LEFT JOIN (SELECT count(1) as `online`,post_id,max(updated) updated FROM `online` where updated > (now() - INTERVAL 10 SECOND) GROUP by post_id ORDER BY updated asc) o2 ON o2.post_id = o1.post_id group by o1.post_id";
            $query = $this->db->select('a.id')
                    ->like('a.title',$search)
                    ->where('online > ',0)
                    ->join("($subquery)  t2","t2.post_id = a.id","left")
                    ->group_by('a.id') 
                    ->get('album a');
            return $query->num_rows();
        }
        else{
            $query = $this->db->like('title',$search)
                    ->get('album');
        }
        return $query->num_rows();
    }

    //Store media
    function store_album($data)
    {
        $this->db->insert('album',$data);
    }

    //Get Albums Data
    function albums_data($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('album')->row();
    }

    //Update Albums data
    function update_albums($data,$id)
    {
        $this->db->where('id',$id);
        return $this->db->update('album',$data);
    }

    //Posts Delete
    function albums_delete($id)
    {
        $this->db->where('id',$id);
        $this->db->delete('album');
    }

    function album_data($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('album')->row();
    }

    function post_data($id)
    {
        $qq = "select * from posts where Id IN($id) order by field(id,$id)";
        return $this->db->query($qq)->result();
        
        $post_ids = (explode(',',$id));
        $this->db->where_in('id',$post_ids);
        return $this->db->get('posts')->result();
    }
    
    /* function activealbums($limit,$start,$col,$dir)
    {   
       $query = $this->db->limit($limit,$start)
                ->where('active','1')
                ->order_by($col,$dir)
                ->get('album');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }  
    } */
	
	function activealbums($limit,$start,$col,$dir)
{   
	$subquery = "SELECT o1.post_id,COALESCE(o2.online,0) online ,IFNULL(max(o2.updated), max(o1.updated)) updated FROM `online` o1 LEFT JOIN (SELECT count(1) as `online`,post_id,max(updated) updated FROM `online` where updated > (now() - INTERVAL 10 SECOND) GROUP by post_id ORDER BY updated asc) o2 ON o2.post_id = o1.post_id group by o1.post_id";

       $query = $this->db->select('a.*,COALESCE(t2.online,0) online,t2.updated')
                ->join("($subquery)  t2","t2.post_id = a.id","left")
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->group_by('a.id')
                ->get('album a');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
}

    function useractivealbums($limit,$start,$col,$dir,$user_id)
    {   
       $query = $this->db->where('user_id',$user_id)
                ->where('active',1)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('album');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function screen_albums_search($limit,$start,$search,$col,$dir)
    {
        $query = $this->db->where('active',1)
                ->like('title',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('album');
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function screen_albums_search_count($search)
    {
        $query = $this->db->where('active',1)
                ->like('title',$search)
                ->get('album');
    
        return $query->num_rows();
    }

    //Get Albums Data
    function albums_count($id)
    {
        $this->db->where('user_id',$id);
        return $this->db->get('album')->num_rows();
    }
    //Get User Screen Count
    function screen_count($id)
    {
        $this->db->where('active',1)->where('user_id',$id);
        return $this->db->get('album')->num_rows();
    }
    //Get All Screen Count
    function allscreen_count()
    {   
        $query = $this->db->where('active',1)->get('album');
        return $query->num_rows();  
    }
    //Get Active Albums Count
    function all_active_album_count()
    {   
        $query = $this->db->where('active',1)->get('album');
        return $query->num_rows();  
    }
    function user_albums_search($limit,$start,$search,$col,$dir,$user_id)
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
    function user_albums_search_count($search,$user_id)
    {
        $query = $this->db->where('user_id',$user_id)->like('title',$search)
                ->get('posts');
    
        return $query->num_rows();
    }

}