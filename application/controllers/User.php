<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct() {
     parent::__construct();
     $this->load->helper(array('form', 'url')); 
     $this->load->model('User_model','Um');
     $this->load->model('Register_model','reg_model');
  }

	public function index()
	{
    $data['yield']       = 'user/index';
		$this->load->view('layouts/default',$data);
	}

  public function create()
  {
    $data['yield']       = 'user/create';
    $this->load->view('layouts/default',$data);
  }

  public function store(){
    $this->load->library('form_validation');
    $this->form_validation->set_rules('user_name', 'Username', 'required');
    $this->form_validation->set_rules('first_name', 'Firstname', 'required');
    $this->form_validation->set_rules('last_name', 'Lastname', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('user_role', 'User Role', 'required');

    $data['user_name'] = $this->input->post('user_name');
    $data['first_name'] = $this->input->post('first_name');
    $data['last_name'] = $this->input->post('last_name');
    $data['email'] = $this->input->post('email');
    $data['password'] = $this->input->post('password');
    $data['user_role'] = $this->input->post('user_role');   

    $config['upload_path'] = './assets/profile'; 
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size'] = '5000';
    $this->load->library('upload',$config);
    $this->upload->do_upload('image');
    $uploadData = $this->upload->data();
    $data['image'] = $uploadData['file_name'];
   
    if ($this->form_validation->run() == FALSE)
    {
        echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else { 
      $last_id = $this->reg_model->storeUser($data);
      echo json_encode(['status'=>true,'message'=>'User Saved Successfully.','redirect'=>base_url('user/')]);
    } 
  }

  public function destroy($id){
    $this->Um->user_delete($id);
    echo json_encode(['status'=>true,'message'=>'User Deleted Successfully.']);
  }

  public function edit($id){
    $data['yield']       = 'user/edit';
    $userdata = $this->session->userdata('user');
    $data['user'] = $this->Um->user_data($id);
    if ($userdata['user_role'] == 0) {
        redirect('login/dashboard');
    }
    elseif(!$data['user']){
        redirect('user');
    }
    $this->load->view('layouts/default',$data);
  }

  public function update(){
    $id = $this->input->post('id');
    $userdata = $this->Um->user_data($id);
    if($this->input->post('email') != $userdata->email) {
       $is_unique =  '|is_unique[users.email]';
    }
    elseif($this->input->post('user_name') != $userdata->user_name) {
       $is_unique =  '|is_unique[users.user_name]';
    }
    else {
       $is_unique =  '';
    }
    $this->load->library('form_validation');
    $this->form_validation->set_rules('user_name', 'Username', 'required'.$is_unique,array('is_unique' => 'The user name already exists.'));
    $this->form_validation->set_rules('first_name', 'Firstname', 'required');
    $this->form_validation->set_rules('last_name', 'Lastname', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required'.$is_unique,array('is_unique' => 'The email address already exists.'));
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('user_role', 'User Role', 'required');

    $data['user_name'] = $this->input->post('user_name');
    $data['first_name'] = $this->input->post('first_name');
    $data['last_name'] = $this->input->post('last_name');
    $data['email'] = $this->input->post('email');
    $data['password'] = $this->input->post('password');
    $data['user_role'] = $this->input->post('user_role');
    //image upload
    $oldimage = $this->input->post('oldimage');
    $current_img = $oldimage;
    $config['upload_path'] = './assets/profile'; 
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size'] = '5000';
    $this->load->library('upload',$config);
    if($this->upload->do_upload('image')){
      $uploadData = $this->upload->data();
      $current_img = $uploadData['file_name'];
    }
    $data['image'] = $current_img; 
    $id = $this->input->post('id');   
    if ($this->form_validation->run() == FALSE)
    {
      echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else {
      $this->Um->updateUser($data,$id);
      echo json_encode(['status'=>true,'message'=>'User Updated Successfully.','redirect'=>base_url('user')]);
    } 
  }

  public function datatable(){
        $columns = array( 'user_name','email','password','user_role','action',);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Um->alluser_count();
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $users = $this->Um->allusers($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $users =  $this->Um->user_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Um->user_search_count($search);
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
              if($user->user_role == 0){
                $user_role = 'User';
              }
              else if($user->user_role == 1){
                $user_role = 'Admin';
              }
              else if($user->user_role == 2){
                $user_role = 'Super Admin';
              }
              //print_r($user->id);
              $userdata = $this->session->userdata('user');
              $delete =  base_url('user/destroy/'.$user->id);
              $edit =  base_url('user/edit/'.$user->id);
              $nestedData['user_name'] = mb_strimwidth($user->user_name, 0, 20, '...');
              $nestedData['email'] = $user->email;
              $nestedData['user_role'] = $user_role;
              $nestedData['action'] = $userdata['id'] == $user->id? '':'<a href="'.$edit.'"><i class="fa fa-edit
"></i></a>&ensp;|&ensp;<a href="'.$delete.'" class="remove_user" data-id="'.$user->id.'"><i class="fa fa-trash-o"></i></a>';
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

  public function profile(){
    $data['yield']       = 'user/profile';
    $this->load->view('layouts/default',$data);
  }

  public function profile_update(){
    $userdata = $this->session->userdata('user');
    if($this->input->post('email') != $userdata['email']) {
       $is_unique =  '|is_unique[users.email]';
    }
    elseif($this->input->post('user_name') != $userdata['user_name'])
    {
      $is_unique =  '|is_unique[users.user_name]';
    }
    else {
       $is_unique =  '';
    }
    $this->load->library('form_validation');
    $this->form_validation->set_rules('user_name', 'Username', 'required'.$is_unique,array('is_unique' => 'The user name already exists.'));
    $this->form_validation->set_rules('first_name', 'Firstname', 'required');
    $this->form_validation->set_rules('last_name', 'Lastname', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required'.$is_unique,array('is_unique' => 'The email address already exists.'));
    $this->form_validation->set_rules('password', 'Password', 'required');    
    if ($this->form_validation->run() == FALSE)
    {
      echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else {
      $data['id'] = $userdata['id'];
      $data['user_name'] = $this->input->post('user_name');
      $data['first_name'] = $this->input->post('first_name');
      $data['last_name'] = $this->input->post('last_name');
      $data['email'] = $this->input->post('email');
      $data['password'] = $this->input->post('password');
      $data['user_role'] = $this->input->post('user_role');   
      //image upload
      $oldimage = $this->input->post('oldimage');
      $current_img = $oldimage;
      $config['upload_path'] = './assets/profile'; 
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $config['max_size'] = '5000';
      $this->load->library('upload',$config);
      if($this->upload->do_upload('image')){
        $uploadData = $this->upload->data();
        $current_img = $uploadData['file_name'];
      }
      $data['image'] = $current_img; 
      
      $this->Um->updateUser($data,$userdata['id']);
      $this->session->set_userdata('user',$data);
      // redirect('user/profile');
      echo json_encode(['status'=>true,'message'=>'Profile Updated Successfully.','redirect'=>base_url('user/profile')]);

    } 
  }



}
