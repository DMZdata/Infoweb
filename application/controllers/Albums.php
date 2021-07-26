<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Albums extends CI_Controller {
private $base_encryption_array = array(
  '0'=>'b76',
  '1'=>'d75',
  '2'=>'f74',
  '3'=>'h73',
  '4'=>'j72',
  '5'=>'l71',
  '6'=>'n70',
  '7'=>'p69',
  '8'=>'r68',
  '9'=>'t67',
  'a'=>'v66',
  'b'=>'x65',
  'c'=>'z64',
  'd'=>'a63',
  'e'=>'d62',
  'f'=>'e61',
  'g'=>'h60',
  'h'=>'i59',
  'i'=>'j58',
  'j'=>'g57',
  'k'=>'f56',
  'l'=>'c55',
  'm'=>'b54',
  'n'=>'y53',
  'o'=>'w52',
  'p'=>'u51',
  'q'=>'s50',
  'r'=>'q49',
  's'=>'o48',
  't'=>'m47',
  'u'=>'k46',
  'v'=>'i45',
  'w'=>'g44',
  'x'=>'e43',
  'y'=>'c42',
  'z'=>'a41'
);
	public function __construct() {
       parent::__construct();
       $this->load->helper(array('form', 'url'));
       $this->load->model('Posts_model','Pm');
       $this->load->model('Album_model','Am');
       $this->load->model('User_model','Um');
    }

	public function index($id='')
	{
    if ($id != '') {
      $data['id'] = $id;
      $this->load->view('album/post_slider',$data);
    }
    else{
      $data['yield']       = 'album/index';
    $this->load->view('layouts/default',$data);
    }
    
	} 

  public function heartbeat(){
    $post_id = $this->my_custom_decode($_POST['post_id']);
    $ip_addr = $this->input->ip_address();
    $this->Pm->heartbeat($post_id,$ip_addr);
  }

  public function create()
  {
    $data['posts'] = $this->Pm->get_allposts_data();
    $data['yield']       = 'album/create';
    $this->load->view('layouts/default',$data);
  }

  public function store()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('title', 'Album Title', 'required');
    $this->form_validation->set_rules('type', 'Type', 'required');
    $this->form_validation->set_rules('post_option', 'Post Option', 'required');
    /*$this->form_validation->set_rules('duration', 'Duration', 'required');*/
    $this->form_validation->set_rules('area', 'Area', 'required');
    if ($this->form_validation->run() == FALSE)
    {
        echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else { 
      $userdata = $this->session->userdata('user');
      $data['post_ids'] = isset($_POST['post_ids']) ? implode(',',$this->input->post('post_ids')):0;
      $data['user_id'] = $userdata['id'];
      $data['title'] = $this->input->post('title');
      $data['type'] = $this->input->post('type');
      $data['show_title'] = isset($_POST['show_title']) ? $_POST['show_title']:'0';
      $data['post_option'] = $this->input->post('post_option');
      /*$data['duration'] = $this->input->post('duration');*/
      $data['duration'] = '';
      $data['area'] = $this->input->post('area');
      $data['show_date_time'] = isset($_POST['show_date_time']) ? $_POST['show_date_time']:'0';
      $data['active'] = isset($_POST['active']) ? $_POST['active'] : '0';
      $this->Am->store_album($data);
      echo json_encode(['status'=>true,'message'=>'Album Saved Successfully.','redirect'=>base_url('albums')]);       
    }
  }

  public function destroy(){
    $id = $this->input->post('id');
    $this->Am->albums_delete($id);
    echo json_encode(['status'=>true,'message'=>'Screen Deleted Successfully.']);
    //redirect('Posts');
  }

  public function edit($id){
    $data['yield']       = 'album/edit';
    $userdata = $this->session->userdata('user');
    $data['posts'] = $this->Pm->get_allposts_data();
    $data['albums'] = $this->Am->albums_data($id);    
    $this->load->view('layouts/default',$data);
  }

  public function update()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('title', 'Album Title', 'required');
    $this->form_validation->set_rules('type', 'Type', 'required');
    $this->form_validation->set_rules('post_option', 'Post Option', 'required');
    /*$this->form_validation->set_rules('duration', 'Duration', 'required');*/
    $this->form_validation->set_rules('area', 'Area', 'required');
    if ($this->form_validation->run() == FALSE)
    {
        echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else {
      $data['post_ids'] = isset($_POST['post_ids']) ? implode(',',$this->input->post('post_ids')):0;;
      $data['title'] = $this->input->post('title');
      $data['type'] = $this->input->post('type');
      $data['show_title'] = isset($_POST['show_title']) ? $_POST['show_title']:'0';
      $data['post_option'] = $this->input->post('post_option');
      /*$data['duration'] = $this->input->post('duration');*/
      $data['duration'] = '';
      $data['area'] = $this->input->post('area');
      $data['show_date_time'] = isset($_POST['show_date_time']) ? $_POST['show_date_time']:'0';
      $data['active'] = isset($_POST['active']) ? $_POST['active'] : '0';
      $id = $this->input->post('id');      
      $this->Am->update_albums($data,$id);
      echo json_encode(['status'=>true,'message'=>'Album Updated Successfully.','redirect'=>base_url('albums')]);
    }
  }

  public function datatable(){
      
        $columns = array( 'title','id','type','created_at','active',null);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $user_id = $this->session->userdata('user');
  
        $totalData = $this->Am->allalbum_count();
            
        $totalFiltered = $totalData; 
        if(empty($this->input->post('search')['value']))
        {   
          if($user_id['user_role'] == 0){        
            $posts = $this->Am->useralbums($limit,$start,$order,$dir,$user_id['id']);
          }
          else{
            $posts = $this->Am->allalbums($limit,$start,$order,$dir);
            // echo "<pre>"; print_r($posts); echo "</pre>";
            // exit;
          }
        }
        else {
            $search = $this->input->post('search')['value']; 
            if($user_id['user_role'] == 0){   
              $posts = $this->Am->user_albums_search($limit,$start,$search,$order,$dir,$user_id['id']);
              $totalFiltered = $this->Am->user_albums_search_count($search,$user_id['id']);
            }
            else{
              $posts =  $this->Am->albums_search($limit,$start,$search,$order,$dir);
              $totalFiltered = $this->Am->albums_search_count($search);
            }
        }
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
              if($post->online > 0){
                $active = '<p class="badge badge-success" align="center">&nbsp;</p><br>online '.$post->online.'';
              }
              else {
                $active = '<p class="badge badge-danger" align="center">&nbsp;</p><br>'.$this->time_ago($post->updated).'';
              }

              // if($post->active == 1){
              //   $active = '<p class="badge badge-success" align="center">&nbsp;</p>';
              // }
              // else if($post->active == 0){
              //   $active = '<p class="badge badge-danger" align="center">&nbsp;</p>';
              // }
			  
			  // echo $post->post_ids;
			  // exit;
              $userData = $this->Um->user_data($post->user_id);
			  $post_ids = 0;
              if((count(explode(',',$post->post_ids)) == 1) && $post->post_ids == 0) {
				  $post_ids = $post->post_ids;
			  }else{
				  $post_ids = count(explode(',',$post->post_ids));
			  }
              $delete =  base_url('albums/destroy/'.$post->id);
              $edit =  base_url('albums/edit/'.$post->id);
              $post_id = $this->my_custom_encode($post->id);
              $view =  base_url('album/'.$post_id);
              $nestedData['id'] = $post->id;
              $nestedData['title'] = '<a href="'.$edit.'"/>'.mb_strimwidth($post->title, 0, 20, '...').'</a><p class="text-warning mt-2">'.$post_ids.'&ensp;Posts(S)</p>';
              $nestedData['links'] = '<input type="text" class="copyLink" value="'.$view.'" style="width: 80%;"/><button class="btn btn-primary p-1 rounded-0" data-toggle="tooltip" data-placement="top" title="Copy Link"><i class="fa fa-copy" onclick="copyToLink(\''.$view.'\')"></i></button><a class="external-link"  data-toggle="tooltip" data-placement="top" title="Open In New Tab" href="'.$view.'" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>';
              //$nestedData['links'] = '<input type="text" class="copyLink" value="'.$post_id.'" disabled/><button class="btn btn-primary p-1 rounded-0" data-toggle="tooltip" data-placement="top" title="Copy Link"><i class="fa fa-copy" onclick="copyToLink(\''.$view.'\')"></i></button>';
              $nestedData['type'] = $post->type;
              $nestedData['created'] = date("d-m-Y", strtotime($post->created_at));
              $nestedData['status'] = $active;
              $nestedData['action'] = '<a href="#" class="remove_album" data-id="'.$post->id.'"><i class="fa fa-trash-o"></i></a>';
              $data[] = $nestedData;
            }
        }
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );   
        echo json_encode($json_data); 
    }

  function is_date_time_valid($date) {

    if (date('Y-m-d H:i:s', strtotime($date)) == $date) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function time_ago($date) {
    $is_valid = $this->is_date_time_valid($date);
    
    if ($is_valid) {
      $timestamp = strtotime($date);
      $difference = time() - $timestamp;
      $periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
      $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

      if ($difference > 0) { // this was in the past time
        $ending = "ago";
      } else { // this was in the future time
        $difference = -$difference;
        $ending = "to go";
      }
      
      for ($j = 0; $difference >= $lengths[$j]; $j++)
        $difference /= $lengths[$j];
      
      $difference = round($difference);
      
      if ($difference > 1)
        $periods[$j].= "s";
      
      $text = "$difference $periods[$j] $ending";
      
      return $text;
    } else {
      return ''; //Date Time must be in "yyyy-mm-dd hh:mm:ss" format
    }
  }

  public  function iframe($id)
    {
        $id = $this->my_custom_decode($id);
        $data['posts'] = $this->Am->album_data($id);
        $data['slider'] = $this->Am->post_data($data['posts']->post_ids);
		if ($data['posts']->active == 1) {
			if($_SESSION['user']['id'] != ''){
				$data['loginpopup']       = false;
			  $this->load->view('album/ifream_slider',$data);
			}else{
			  $data['loginpopup']       = true;
			  $this->load->view('album/ifream_slider',$data);
			  // $data['yield']       = 'album/index';
			  // $this->load->view('layouts/default',$data);
			}
		}else{
		$data['loginpopup']       = false;
		$this->load->view('album/ifream_slider',$data);
		}
    }

    private function my_custom_encode($string){
      $string = (string)$string;
      $length = strlen($string);
      $hash = '';
          for ($i=0; $i<$length; $i++) {
              if(isset($string[$i])){
                  $hash .= $this->base_encryption_array[$string[$i]];
              }
          }
      return $hash;
    }


    private function my_custom_decode($hash){
      // this makes keys as values and values as keys /
      $this->base_encryption_array = array_flip($this->base_encryption_array);

      $hash = (string)$hash;
      $length = strlen($hash);
      $string = '';

          for ($i=0; $i<$length; $i=$i+3) {
              if(isset($hash[$i]) && isset($hash[$i+1]) && isset($hash[$i+2]) && isset($this->base_encryption_array[$hash[$i].$hash[$i+1].$hash[$i+2]])){
                  $string .= $this->base_encryption_array[$hash[$i].$hash[$i+1].$hash[$i+2]];
              }
          }
      return $string;
    }


}
