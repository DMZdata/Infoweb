<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
    if($user_id['user_role'] == 0){  
      $data['post_count'] = $this->Pm->posts_count($user_id['id']);
      $data['album_count'] = $this->Am->albums_count($user_id['id']);
      $data['screen_count'] = $this->Am->screen_count($user_id['id']);
    }
    else{
      $data['post_count'] = $this->Pm->allposts_count(); 
      $data['album_count'] = $this->Am->allalbum_count(); 
      // $data['screen_count'] = $this->Am->allscreen_count();
      $data['screen_count'] = $this->Am->allalbum_count(true);
	  // $totalData = $this->Am->allalbum_count(true);
	  // echo "<pre>";
	  // print_r($totalData);
    }
    $data['user_count'] = $this->Um->alluser_count();
    $data['yield']       = 'dashboard';
    $this->load->view('layouts/default', $data);
	}
	
}
