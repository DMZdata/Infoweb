<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
     parent::__construct();
     $this->load->helper(array('form', 'url')); 
     $this->load->model('login_model','Lm');
     $this->load->model('Posts_model','Pm');
     $this->load->model('User_model','Um');
     $this->load->model('Album_model','Am');
     $this->load->library('session');
  }

	public function index()
	{
    $user_id = $this->session->userdata('user');
    if($user_id){
      redirect('dashboard');
    }
    else{
  		$this->load->view('login');
    }
	}

  public function dashboard()
  {
    $user_id = $this->session->userdata('user');
    if($user_id['user_role'] == 0){  
      $data['post_count'] = $this->Pm->posts_count($user_id['id']);
      $data['album_count'] = $this->Am->albums_count($user_id['id']);
    }
    else{
      $data['post_count'] = $this->Pm->allposts_count(); 
      $data['album_count'] = $this->Am->allalbum_count(); 
    }
    $data['user_count'] = $this->Um->alluser_count();
    $data['yield']       = 'dashboard';
    $this->load->view('layouts/default', $data);
  }

  public function user_login(){
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'required');
    $this->form_validation->set_rules('password', 'password', 'required');
    if ($this->form_validation->run() == FALSE)
    {
      echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else{
      $email = $this->input->post('email');
      $password = $this->input->post('password');
      $is_user = $this->Lm->login_user($email,$password);
      if($is_user){
        $this->session->set_userdata('user',$is_user);
        echo json_encode(['status'=>true,'message'=>'Login Successfully.','redirect'=>base_url('dashboard')]);
      }
      else{
        echo json_encode(['status'=>false,'message'=>'incorrect username and password.']);
      }
    }
  }

  public function logout(){
    $this->session->unset_userdata('user');  
    redirect("login"); 
  }

  public function forgot_password()
  {
      $this->load->view('forgot_password');
  }

  public function confirm_password($id)
  {
      $data['id'] = $id;
      $data['user'] = $this->Lm->confirm_userdata($id);
      $this->load->view('confirm_password',$data);
  }

  public function forgot_pass_form()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'required');
    if ($this->form_validation->run() == FALSE)
    {
      echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else{
      $email = $this->input->post('email');
      $data['verify_id'] = uniqid();
      $this->Lm->create_veritycode($email,$data);
      $is_user = $this->Lm->forgot_email($email);
      if($is_user){
        $data['id'] = $is_user->verify_id;
        $this->load->library('email');
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com', 
            'smtp_port' => 465,
            'smtp_user' => 'bhuvakartik018@gmail.com',
            'smtp_pass' => 'kartik@1668',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );
        $this->email->initialize($config);
        
        $from = $this->config->item('smtp_user');
        $to = $this->input->post('email');
        $subject = 'Confirm Password';
        $this->email->set_newline("\r\n");
        $this->email->from('bhuvakartik018@gmail.com','Demo');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($this->load->view('mail/confirm_mail',$data,true));

        if ($this->email->send()) 
        {
          echo json_encode(['status'=>true,'message'=>'Please Check Your Mail.','redirect'=>base_url('login')]);
          exit;
        } 
      }
      else{
        echo json_encode(['status'=>false,'message'=>'incorrect email address.']);
      }
    } 
  }

  public function confirm_pass_form()
  {
    $id = $this->input->post('id');
    $data['password'] = $this->input->post('password');
    $data['verify_id'] = '';
    $confirm_userdata = $this->Lm->confirm_userdata($id);
    if($confirm_userdata){
      $this->Lm->updatePassword($id,$data);
      $this->Lm->remove_veritycode($id,$data);
      echo json_encode(['status'=>true,'message'=>'Forgot Password Successfully.','redirect'=>base_url('login/dashboard')]);
    }
    else{
      echo json_encode(['status'=>false,'message'=>'Link Is Expired.']);
    }
  }


	
}
