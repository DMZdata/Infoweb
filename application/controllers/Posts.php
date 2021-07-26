<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

 use setasign\Fpdi\Fpdi;


class Posts extends CI_Controller {
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
public function crop_img()
  {
    $data['yield']       = 'posts/crop_img';
    $this->load->view('layouts/default',$data);
  }
  public function __construct() {
       parent::__construct();
       $this->load->helper(array('form', 'url')); 
       $this->load->model('Posts_model','Pm');
       $this->load->model('User_model','Um');
    }

  public function index($id='')
  {
    if ($id != '') {
      $data['id'] = $id;
      $this->load->view('posts/post_slider',$data);
    }
    else{
      $data['yield']       = 'posts/index';
    $this->load->view('layouts/default',$data);
    }
    
  }

  public function create()
  {
    $data['yield']       = 'posts/create';
    $this->load->view('layouts/default',$data);
  }

  public function create_pdf()
  {
    $data['yield']       = 'posts/create_pdf';
    $this->load->view('layouts/default',$data);
  }

  public function create_iframe()
  {
    $data['yield']       = 'posts/create_iframe';
    $this->load->view('layouts/default',$data);
  }
   /*
     * file value and type check during validation
     */
  public function file_check($str){
    // var_dump($str);die;
      $allowed_mime_type_arr = array('application/pdf');
      $mime = get_mime_by_extension($_FILES['document']['name']);
      if(isset($_FILES['document']['name']) && $_FILES['document']['name']!=""){
          if(in_array($mime, $allowed_mime_type_arr)){
              return true;
          }else{
              $this->form_validation->set_message('file_check', 'Please select only pdf file.');
              return false;
          }
      }
      else{

          if(isset($_POST['id'])){
          
            return true;
          }else{
             $this->form_validation->set_message('file_check', 'Please choose a file to upload.');
            return false;
          }
          
          
      }
  }

  public function pdf_read(){
    array_map( 'unlink', array_filter((array) glob("assets/upload_pdf/split/*") ) );
    $config['upload_path'] = 'assets/upload_pdf/';
    $config['allowed_types'] = 'pdf';
    // $config['encrypt_name'] = TRUE;
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $new_name = time().".".$ext;
    $config['file_name'] = $new_name;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);
    // var_dump($config['file_name']);die;
    $this->upload->do_upload('image');
    
    $this->split_pdf('assets/upload_pdf/'.$config['file_name'],"assets/upload_pdf/split/");
    $dir    = 'assets/upload_pdf/split/';
    $files1 = scandir($dir);

    echo json_encode($files1);
    unlink("assets/upload_pdf/".$config['file_name']);
  }

  public function split_pdf(string $filename, string $directory)
  {
      $pdf = new Fpdi();
      $pageCount = $pdf->setSourceFile($filename);
      $file = pathinfo($filename, PATHINFO_FILENAME);

      // Split each page into a new PDF
      for ($i = 1; $i <= $pageCount; $i++) {
          $newPdf = new Fpdi();
          $newPdf->addPage();
          $newPdf->setSourceFile($filename);
          $newPdf->useTemplate($newPdf->importPage($i));

          $newFilename = sprintf('%s/%s_%s.pdf', $directory, $file, $i);
          $newPdf->output($newFilename, 'F');
      }
  } 

  public function store()
  {
    $this->load->library('form_validation');
    // $this->form_validation->set_rules('title', 'Album Title', 'required');
    // $this->form_validation->set_rules('content', 'Content', 'required');
    // $this->form_validation->set_rules('image', 'Image', 'required');
    // $this->form_validation->set_rules('duration', 'Duration', 'required|integer');
    $rules = array(
        array(
                'field' => 'title',
                'label' => 'Album Title',
                'rules' => 'required'
        ),
        array(
                'field' => 'content',
                'label' => 'Content',
                'rules' => 'required',
                
        ),
        array(
                'field' => 'image',
                'label' => 'Image',
                'rules' => 'required'
        ),
         array(
                'field' => 'duration',
                'label' => 'Duration',
                'rules' => 'required|integer',
                'errors' => array(
                        'integer' => 'The %s field must contain a number.',
                ),
        )
);
    $this->form_validation->set_rules($rules);
    if ($this->form_validation->run() == FALSE)
    {
        echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else { 
      $userdata = $this->session->userdata('user');
      $data['user_id'] = $userdata['id'];
      if(isset($_POST['image']) && $_POST['image'] != '')
      {
        $pre_image = $_POST['image'];
        $image_array_1 = explode(";", $pre_image);
        $image_array_2 = explode(",", $image_array_1[1]);
        $base_image = base64_decode($image_array_2[1]);
        $image_name = time() . '.png';
        file_put_contents('assets/upload/'.$image_name, $base_image);
        $data['image'] = $image_name;
      }
      $data['title'] = $this->input->post('title');
      $data['content'] = $this->input->post('content');
      $data['duration'] = $this->input->post('duration');
      $this->Pm->store_post($data);
      echo json_encode(['status'=>true,'message'=>'Post Saved Successfully.','redirect'=>base_url('posts')]);       
    }
  }

  public function store1(){
   
        $this->load->library('form_validation');
        // $this->form_validation->set_rules('title', 'Title', 'required');
        // $this->form_validation->set_rules('content', 'Content', 'required');
        // $this->form_validation->set_rules('document', '', 'callback_file_check');
        // $this->form_validation->set_rules('duration', 'Duration', 'required|integer');
         $rules = array(
                array(
                        'field' => 'title',
                        'label' => 'Album Title',
                        'rules' => 'required'
                ),
                array(
                        'field' => 'content',
                        'label' => 'Content',
                        'rules' => 'required',
                        
                ),
                array(
                        'field' => 'document',
                        'label' => '',
                        'rules' => 'callback_file_check'
                ),
                 array(
                        'field' => 'duration',
                        'label' => 'Duration',
                        'rules' => 'required|integer',
                        'errors' => array(
                                'integer' => 'The %s field must contain a number.',
                        ),
                )
          );
          $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE)
        {
            echo json_encode(['status'=>false,'message'=>validation_errors()]);
        }
        else { 
         
          $userdata = $this->session->userdata('user');

          $config['upload_path'] = 'assets/upload_pdf/pdf_file/';
          $config['allowed_types'] = 'pdf';
          
          $ext  = pathinfo($_FILES["document"]['name'], PATHINFO_EXTENSION);
          $new_name = time().".".$ext;
          $config['file_name'] = $new_name;

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          $this->upload->do_upload('document'); //document is the name of your post file
           array_map( 'unlink', array_filter((array) glob("assets/upload_pdf/split/*") ) );
          $this->split_pdf('assets/upload_pdf/pdf_file/'.$config['file_name'],"assets/upload_pdf/split/");
          $dir    = 'assets/upload_pdf/split/';
          $files1 = scandir($dir);
          $array_pdf = array();
          foreach ($files1 as $pdf) {
            $ext = pathinfo($pdf, PATHINFO_EXTENSION);
            if($ext){
              copy("assets/upload_pdf/split/".$pdf, "assets/upload_pdf/pdf_file/split/".$pdf);
              $array_pdf[] = $pdf;
            }
          }
          // var_dump($array_pdf)
          $data['user_id'] = $userdata['id'];
          $data['image'] = json_encode($array_pdf);
          $data['type'] = "pdf";
          $data['title'] = $this->input->post('title');
          $data['content'] = $this->input->post('content');
          $data['duration'] = $this->input->post('duration');
          $this->Pm->store_post($data);
          array_map( 'unlink', array_filter((array) glob("assets/upload_pdf/split/*") ) );
          echo json_encode(['status'=>true,'message'=>'Post Saved Successfully.','redirect'=>base_url('posts')]); 

        }
  }
 
  public function valid_url($url){
    // var_dump($_POST['url']);die;
     if (isset($_POST['url']) && $_POST['url']!=""){
       if(filter_var($_POST['url'], FILTER_VALIDATE_URL)){
          return TRUE;
       }else{
          $this->form_validation->set_message('file_check', 'Please Enter valid URL.');
          return false;
       }
     }else {
        // if(isset($_POST['id'])){
          
        //   return true;
        // }else{
           $this->form_validation->set_message('file_check', 'Please Enter iframe URL.');
          return false;
        // }
            
     }
  }

  public function store2()
  {
    $this->load->library('form_validation');
    // $this->form_validation->set_rules('url', '', 'callback_valid_url');
    // $this->form_validation->set_rules('title', 'Title', 'required');
    // $this->form_validation->set_rules('content', 'Content', 'required');
    // $this->form_validation->set_rules('duration', 'Duration', 'required|integer');
    $rules = array(
                array(
                        'field' => 'title',
                        'label' => 'Album Title',
                        'rules' => 'required'
                ),
                array(
                        'field' => 'content',
                        'label' => 'Content',
                        'rules' => 'required',
                        
                ),
                array(
                        'field' => 'url',
                        'label' => '',
                        'rules' => 'callback_valid_url'
                ),
                 array(
                        'field' => 'duration',
                        'label' => 'Duration',
                        'rules' => 'required|integer',
                        'errors' => array(
                                'integer' => 'The %s field must contain a number.',
                        ),
                )
          );
          $this->form_validation->set_rules($rules);
    if ($this->form_validation->run() == FALSE)
    {
        echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else { 
      $userdata = $this->session->userdata('user');
      $data['user_id'] = $userdata['id'];
     
      $data['image'] = $this->input->post('url');
      $data['title'] = $this->input->post('title');
      $data['type'] = "iframe";
      $data['content'] = $this->input->post('content');
      $data['duration'] = $this->input->post('duration');
      $this->Pm->store_post($data);
      echo json_encode(['status'=>true,'message'=>'Post Saved Successfully.','redirect'=>base_url('posts')]);       
    }
  }

  private function set_upload_options()
    {   
        //upload an image options
        $config = array();
        $config['upload_path'] = './posts/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        return $config;
    }

  public function destroy(){
    $id = $this->input->post('id');
    $this->Pm->posts_delete($id);
    echo json_encode(['status'=>true,'message'=>'Post Deleted Successfully.']);
    //redirect('Posts');
  }

  public function media_delete(){
    $id = $this->input->post('id');
    $this->Pm->single_media_delete($id);
    echo json_encode(['status'=>true,'message'=>'Post Deleted Successfully.']);
  }

  public function edit($id){
    $data['yield']       = 'posts/edit';
    $userdata = $this->session->userdata('user');
    $data['post'] = $this->Pm->posts_data($id);    
    $this->load->view('layouts/default',$data);
  }

  public function editpdf($id){
    $data['yield']       = 'posts/edit_pdf';
    $userdata = $this->session->userdata('user');
    $data['post'] = $this->Pm->posts_data($id);  

    $this->load->view('layouts/default',$data);
  }
  public function editiframe($id){ 
    $data['yield']       = 'posts/edit_iframe';
    $userdata = $this->session->userdata('user');
    $data['post'] = $this->Pm->posts_data($id);  
    // var_dump($data['post']);die;
    $this->load->view('layouts/default',$data);
  }


  public function update()
  {
    $this->load->library('form_validation');
    // $this->form_validation->set_rules('title', 'Album Title', 'required');
    // $this->form_validation->set_rules('content', 'Contant', 'required');
    // $this->form_validation->set_rules('duration', 'Duration', 'required|integer');

     $rules = array(
        array(
                'field' => 'title',
                'label' => 'Album Title',
                'rules' => 'required'
        ),
        array(
                'field' => 'content',
                'label' => 'Content',
                'rules' => 'required',
                
        ),
        array(
                'field' => 'image',
                'label' => 'Image',
                'rules' => 'required'
        ),
         array(
                'field' => 'duration',
                'label' => 'Duration',
                'rules' => 'required|integer',
                'errors' => array(
                        'integer' => 'The %s field must contain a number.',
                ),
        )
);
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE)
    {
        echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else {
      $data['title'] = $this->input->post('title');
      $data['content'] = $this->input->post('content');
      $data['duration'] = $this->input->post('duration');
      $id = $this->input->post('id');
      $oldimage = $this->input->post('oldimage');
      $current_img = $oldimage;
      if (!empty($_FILES['image']['name'])) {              
        if(isset($_POST['image']) && $_POST['image'] != '')
        {
          $pre_image = $_POST['image'];
          $image_array_1 = explode(";", $pre_image);
          $image_array_2 = explode(",", $image_array_1[1]);
          $base_image = base64_decode($image_array_2[1]);
          $image_name = time() . '.png';
          file_put_contents('assets/upload/'.$image_name, $base_image);
          $current_img = $image_name;
        }
      }
      $data['image'] = $current_img;
      $this->Pm->update_posts($data,$id);
      echo json_encode(['status'=>true,'message'=>'Post Updated Successfully.','redirect'=>base_url('posts')]);
    }
  }

  public function update1(){
        $this->load->library('form_validation');
        // $this->form_validation->set_rules('title', 'Title', 'required');
        // $this->form_validation->set_rules('content', 'Content', 'required');
        // $this->form_validation->set_rules('document', '', 'callback_file_check');
        // $this->form_validation->set_rules('duration', 'Duration', 'required|integer');

         $rules = array(
                array(
                        'field' => 'title',
                        'label' => 'Album Title',
                        'rules' => 'required'
                ),
                array(
                        'field' => 'content',
                        'label' => 'Content',
                        'rules' => 'required',
                        
                ),
                array(
                        'field' => 'document',
                        'label' => '',
                        'rules' => 'callback_file_check'
                ),
                 array(
                        'field' => 'duration',
                        'label' => 'Duration',
                        'rules' => 'required|integer',
                        'errors' => array(
                                'integer' => 'The %s field must contain a number.',
                        ),
                )
          );
          $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE)
        {
            echo json_encode(['status'=>false,'message'=>validation_errors()]);
        }
        else { 

          $userdata = $this->session->userdata('user');
          if(!isset($_FILES["document"]['name']) && $_FILES["document"]['name'] == ""){
             $id = $this->input->post('id');
              $data['user_id'] = $userdata['id'];
             
              $data['title'] = $this->input->post('title');
              $data['content'] = $this->input->post('content');
              $data['duration'] = $this->input->post('duration');
              $this->Pm->update_posts($data,$id);
          }else{
            $config['upload_path'] = 'assets/upload_pdf/pdf_file/';
            $config['allowed_types'] = 'pdf';
            $ext  = pathinfo($_FILES["document"]['name'], PATHINFO_EXTENSION);
            $new_name = time().".".$ext;
            $config['file_name'] = $new_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $this->upload->do_upload('document'); //document is the name of your post file

            array_map( 'unlink', array_filter((array) glob("assets/upload_pdf/split/*") ) );
            $this->split_pdf('assets/upload_pdf/pdf_file/'.$config['file_name'],"assets/upload_pdf/split/");
            $dir    = 'assets/upload_pdf/split/';
            $files1 = scandir($dir);
            $array_pdf = array();
            foreach ($files1 as $pdf) {
              $ext = pathinfo($pdf, PATHINFO_EXTENSION);
              if($ext){
                copy("assets/upload_pdf/split/".$pdf, "assets/upload_pdf/pdf_file/split/".$pdf);
                $array_pdf[] = $pdf;
              }
            }
            // var_dump($array_pdf);die;
            $id = $this->input->post('id');
            $data['user_id'] = $userdata['id'];
            $data['image'] = json_encode($array_pdf);
            $data['type'] = "pdf";
            $data['title'] = $this->input->post('title');
            $data['content'] = $this->input->post('content');
            $data['duration'] = $this->input->post('duration');
            $this->Pm->update_posts($data,$id);
          }
          

          array_map( 'unlink', array_filter((array) glob("assets/upload_pdf/split/*") ) );
          echo json_encode(['status'=>true,'message'=>'Post Updated Successfully.','redirect'=>base_url('posts')]);
        }
  }

  public function update2()
  {
    $this->load->library('form_validation');
    // $this->form_validation->set_rules('url', '', 'callback_valid_url');
    // $this->form_validation->set_rules('title', 'Title', 'required');
    // $this->form_validation->set_rules('content', 'Contant', 'required');
    // $this->form_validation->set_rules('duration', 'Duration', 'required|integer');

     $rules = array(
          array(
                  'field' => 'title',
                  'label' => 'Album Title',
                  'rules' => 'required'
          ),
          array(
                  'field' => 'content',
                  'label' => 'Content',
                  'rules' => 'required',
                  
          ),
          array(
                  'field' => 'url',
                  'label' => '',
                  'rules' => 'callback_valid_url'
          ),
           array(
                  'field' => 'duration',
                  'label' => 'Duration',
                  'rules' => 'required|integer',
                  'errors' => array(
                          'integer' => 'The %s field must contain a number.',
                  ),
          )
    );
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE)
    {
        echo json_encode(['status'=>false,'message'=>validation_errors()]);
    }
    else {
      $id = $this->input->post('id');
      $data['image'] = $this->input->post('url');
      $data['title'] = $this->input->post('title');
      $data['content'] = $this->input->post('content');
      $data['duration'] = $this->input->post('duration');
      
      $this->Pm->update_posts($data,$id);
      echo json_encode(['status'=>true,'message'=>'Post Updated Successfully.','redirect'=>base_url('posts')]);
    }
  }

  public function datatable(){
        $columns = array( 'title','id','content','image',null);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $user_id = $this->session->userdata('user');
          


        if($user_id['user_role'] == 0){  
          $totalData = $this->Pm->posts_count($user_id['id']);
        }
        else{
          $totalData = $this->Pm->allposts_count();
        }  
        $totalFiltered = $totalData; 
        
        if(empty($this->input->post('search')['value']))
        {   
          if($user_id['user_role'] == 0){        
            $posts = $this->Pm->userposts($limit,$start,$order,$dir,$user_id['id']);
          }
          else{
            $posts = $this->Pm->allposts($limit,$start,$order,$dir);
           
          }
        }
        else {
            $search = $this->input->post('search')['value']; 
            if($user_id['user_role'] == 0){        
              $posts = $this->Pm->user_posts_search($limit,$start,$search,$order,$dir,$user_id['id']);
              $totalFiltered = $this->Pm->user_posts_search_count($search,$user_id['id']);
            }
            else{
              $posts =  $this->Pm->posts_search($limit,$start,$search,$order,$dir);
              $totalFiltered = $this->Pm->posts_search_count($search);
            }

            
        }
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
              $userData = $this->Um->user_data($post->user_id);
              $convertDate = $this->time_elapsed_string($post->created_at);
              $delete =  base_url('posts/destroy/'.$post->id);
              $edit =  base_url('posts/edit/'.$post->id);
              $edit1 =  base_url('posts/editpdf/'.$post->id);
              $edit2 =  base_url('posts/editiframe/'.$post->id);
              $post_id = $this->my_custom_encode($post->id);
              $view =  base_url('post/'.$post_id);

              $nestedData['content'] = mb_strimwidth($post->content, 0, 70, '...');
              if($post->type == "image"){
                $nestedData['title'] = '<a href="'.$edit.'" />'.mb_strimwidth($post->title, 0, 70, '...').'</a>';
                $nestedData['image'] = '<i class="fa fa-picture-o" title="Image"></i><!--img src="'.base_url().'assets/upload/'.$post->image.'" style="height: 50px;width: 50px;border-radius: 0;" /-->';
              }else if($post->type == "iframe"){
                $nestedData['title'] = '<a href="'.$edit2.'" />'.mb_strimwidth($post->title, 0, 70, '...').'</a>';
                $nestedData['image'] = '<i class="fa fa-globe" title="I-frame"></i><!--img src="'.base_url('assets/upload/iframe-icon.png').'" style="height: 50px;width: 50px;border-radius: 0;" /-->';
              }else{
                $nestedData['title'] = '<a href="'.$edit1.'" />'.mb_strimwidth($post->title, 0, 70, '...').'</a>';
                $nestedData['image'] = '<i class="fa fa-file-pdf-o" title="PDF"></i><!--img src="'.base_url('assets/upload/pdf-icon.png').'" style="height: 50px;width: 50px;border-radius: 0;" /-->';
              }
              //$nestedData['image'] = $post->image;
               $nestedData['links'] = '<input type="text" class="copyLink" value="'.$post_id.'" disabled/><button class="btn btn-primary p-1 rounded-0" data-toggle="tooltip" data-placement="top" title="Copy Link"><i class="fa fa-copy" onclick="copyToLink(\''.$view.'\')"></i></button>';
              $nestedData['action'] = '<a href="#" class="remove_post " data-id="'.$post->id.'"><i class="fa fa-trash-o"></i></a>';
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

  private function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  function iframe($id) {
      $id = $this->my_custom_decode($id);
      //$data['posts'] = $this->Pm->album_data($id);
      $data['slider'] = $this->Pm->post_data($id);

      // $data['slider'][0]['type'] = "pdf";
      // $data['slider'][0]['content'] = "sdcsd";
      // $data['slider'][0]['image'] = "1612445645sample_1.pdf";
      $this->load->view('posts/ifream_slider',$data);
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
