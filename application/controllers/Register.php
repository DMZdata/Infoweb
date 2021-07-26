<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {
  
	public function __construct() {
       parent::__construct();
       $this->load->helper(array('form', 'url')); 
       $this->load->model('register_model','reg_model');
    }

	public function index()
	{
		  $this->load->view('register');
	}

	public function user_register(){
		$this->load->library('form_validation');
    $this->form_validation->set_rules('user_name', 'User Name', 'required|is_unique[users.user_name]',array('is_unique' => 'The user name already exists.'));
		$this->form_validation->set_rules('first_name', 'User First Name', 'required');
    $this->form_validation->set_rules('last_name', 'User Lats Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]',array('is_unique' => 'The email address already exists.'));
		$this->form_validation->set_rules('password', 'Password', 'required');
  	$this->form_validation->set_rules('confirm_pass', 'Password Confirmation', 'required|matches[password]');

        $data['user_name'] = $this->input->post('user_name');
        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['email'] = $this->input->post('email');
        $data['password'] = $this->input->post('password');
       	

		if ($this->form_validation->run() == FALSE)
    {
      echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else { 
		  $this->reg_model->storeUser($data);
		  echo json_encode(['status'=>true,'message'=>'Register Successfully.','redirect'=>base_url('login')]);
    } 
	}

}
