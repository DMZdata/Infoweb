<?php
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/> -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/shared/slick.css')?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/shared/slick-theme.css')?>"/>
<link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/font-awesome/css/font-awesome.min.css')?>" />

 <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/shared/style.css')?>">
  <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css')?>">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet"/>
   <!-- toastr css -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/shared/toastr.css')?>">

<style type="text/css">
 * {
    box-sizing: border-box;
  }
  .slick-dotted.slick-slider {
      margin-bottom: 0 !important;
  }
  ul.slick-dots {
      top: 0 !important;
      bottom: auto !important;
      position: fixed !important;
      left: 0 !important;
      text-align: left !important;
      padding: 10px !important;
  }
  body {
    margin: 0; padding: 0;
    background: #000;
  }
  /*.tablet .banner_slide {
    max-width: 800px;
    margin:0 auto;
  }
  .mobile .banner_slide {
    max-width: 473px;
    margin:0 auto;
  }*/
  .panel_resp {
    position: absolute;
    left: 0;
    width: 100%;
    padding: 20px;
    text-align: center;
    z-index: 9999999;
    top: 25px;
  }
  .panel_resp ul {
    list-style: none;
    margin: 0; padding: 0;
  }
  .panel_resp ul li {
    position: relative;
    display: inline-block;
    margin: 0 1px;
  }
  .panel_resp ul li  a{
    display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      background: #000;
      width: 30px;
      height: 30px;
      line-height: 30px;
      text-align: center;
      border-radius: 3px;
      font-size: 16px;
      color: #fff;
  }
  .body_area {
    background: #eee;
    height: <?=$posts->show_date_time == 1?'80vh':'100vh'?>;
    overflow:hidden;
    overflow-x: hidden;
    position: relative;
  }
    .covrimage {
        background: url(10.png) repeat #0BA5F2;
  }
  .covrimage .bgimagee {
    background-repeat: no-repeat;
    background-position: top left;
    background-size: cover;
    width: 100%;
    /*height: 100%;*/
    height: <?=$posts->show_date_time == 1?'80vh':'100vh'?>;
    position: relative;
  }
  .covrimage .caption {
    position: absolute;
    bottom: 0;
    background: rgb(237, 237, 237, 0.6); /*#ededed*/
    width: 100%;
    height: 20%;
    padding: 30px 25px;
      font-size: 25px;
    display: flex;
      align-items: center;
        }
  .footer_area {
    position: relative;
    z-index: 999;
    background: #fff;
    padding: 20px;
    height: 20vh;
    display: flex;
      align-items: center;
        }
  .footer_area p {
    margin: 0; padding: 0;
  }
  .footer_area p:last-child {
    margin: 0;
  }
  .time {
    font-weight: bold;
    font-size: 35px;
    text-transform: uppercase;
  }
  .date {
    font-weight: normal;
    font-size: 25px;
    text-transform: uppercase;
    color: #aaa;
  }
  .slick-dots li button::before {
    font-size: 20px;
  }
  .panel_resp ul li a{
    text-decoration: auto;
  }
  .panel_resp ul li a{
display:none!important;
}

.banner_slide:hover .panel_resp ul li a{
display:block!important;
}
</style>
</head>
<body>
  <?php echo $media->image; 
  if(isset($loginpopup) && $loginpopup != true){
    ?>

  <div class="banner_slide">
   
    <div class="body_area image fullpane canvas  ">
     
      <div class="slidesss">
        <?php 
         // var_dump($slider);
        $duration = [];
		$typeDuration = ["","",""];
        foreach ($slider as $key => $media) {
          if ($media->duration !== '') {
            $slideduration = $media->duration;
          }else{
			  
			// default seconds according to the type
			switch ($media->type) {
				case "pdf":
					$slideduration = 5; //5 sec for pdf
					break;
				case "image":
					$slideduration = 2; //2 sec for image
					break;
				case "iframe":
					$slideduration = 5; //5 sec for iframe
					break;
			}            
          }
		  
		  /// multiply second to milisecond for JS code
		  $duration[] = $slideduration*1000;
		  
          // var_dump($media);die;
          if($media->type == "pdf"){  
            $pdfpages = json_decode($media->image);
            foreach ($pdfpages as $pdfpage) {
            ?>
            <center>
              <div class="covrimage">
                <div class="bgimagee">
                  
                    <embed width="400" height="max-content" style="height: inherit;" name="plugin" src="<?php echo base_url('assets/upload_pdf/pdf_file/split/'.$pdfpage.'#toolbar=0&navpanes=0') ?>" class="active" type="application/pdf">
                
          <?php if($posts->show_title == 1) { ?>
            <div class="caption"><div><?php echo $media->content; ?></div></div>
          <?php } ?>
                </div>
              </div>
                </center>
            <?php 
            }
          }else if($media->type == "image"){
            // $type = $media->type;
            // if($type=='image'){
             ?>
              <div class="covrimage">
                  <div class="bgimagee" style="background-image:url(<?php echo base_url('assets/upload/'.$media->image) ?>)">
                    <?php if($posts->show_title == 1) { ?>
            <div class="caption"><div><?php echo $media->content; ?></div></div>
          <?php } ?>
                  </div>
              </div>
            <?php 
            // } 
          }else if($media->type == "iframe"){ ?>
               <div class="covrimage">
                  <div class="bgimagee" >
                    <!-- <center>  -->
                      <iframe is="x-frame-bypass" src="<?php echo $media->image; ?>" height="max-content" style="width: 100%;height: inherit;"></iframe>
                      <!-- height: 650px; -->
                    <!-- </center> -->
          <?php if($posts->show_title == 1) { ?>
            <div class="caption"><div><?php echo $media->content; ?></div></div>
          <?php } ?>
                  </div>
              </div>
              <?php
          } 
        }
        ?>
      </div>
      
      
    </div>

    <?php if($posts->show_date_time == 1) { ?>
      <div class="footer_area">
      <div class="div">
        <p class="time"><?php echo date('H:i');?></p>
        <p class="date"><?php echo date("d M", strtotime(date('Y-m-d')));?></p>
      </div>
    </div>
    <?php } ?>
    <div class="panel_resp">
          <ul class="modes">
            <?php
            $userdata = $this->session->userdata('user');
            if($userdata != ''){ ?>   
              <!-- <li><a href="javascript:;" data-mode="fullsize"><i class="fa fa-expand"></i></a></li>
              <li><a href="javascript:;" data-mode="landscape"><i class="fa fa-laptop"></i></a></li>
              <li><a href="javascript:;" data-mode="portrait"><i class="fa fa-tablet"></i></a></li>
              <li><a href="javascript:;" data-mode="device"><i class="fa fa-mobile"></i></a></li>
               -->
              <li><a href="javascript:;" class="play-pause pause_btn"><i class="fa fa-pause"></i></a></li>
              <li><a href="javascript:;" class="pause-play play_btn" style="display: none;"><i class="fa fa-play"></i></a></li>
            <?php } ?>
          </ul>
      </div> 
  </div>
<?php } ?>
  <button type="button" class="btn btn-info btn-lg" id="popup_button" data-toggle="modal" data-target="#myModal" style="display: none;">Open Modal</button>
      <!-- Modal -->
      <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <!-- <div class="modal-header" style="border-bottom:0px;">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modal Header</h4>
            </div> -->
            <div class="modal-body" style="padding: 0px;">
              <style type="text/css">
                .input-group {
                    position: relative !important;
                    display: flex !important;
                     flex-wrap: nowrap; 
                    align-items: stretch;
                    width: 100%;
                }
              </style>
              <!-- <a class="btn btn-primary btn-fw" href="<?php echo base_url('login') ?>">login</a> -->
                <div class="container-scroller">
                  <div class="container-fluid page-body-wrapper full-page-wrapper" style="padding: 0px;">
                    <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one" style="padding: 0px;">
                      <div class="w-100">
                        <div class="col-lg-12 col-sm-12 col-md-12 mx-auto">
                          <h3 class="title text-center mb-4">Login</h3>
                          <div class="auto-form-wrapper">
                            <form action="<?php echo base_url('login/user_login') ?>" method="post" id="login_form">
                              <div class="form-group">
                                <label class="label">Email/Username *</label>
                                <div class="input-group">
                                  <input type="text" name="email" class="form-control" placeholder="Username or Email" autocomplete="off">
                                  <div class="input-group-append">
                                    <span class="input-group-text">
                                      <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="label">Password *</label>
                                <div class="input-group">
                                  <input type="password" name="password" class="form-control" id="password" placeholder="*********" autocomplete="off">
                                  <div class="input-group-append">
                                    <span class="input-group-text">
                                      <span toggle="#password" class="fa fa-fw field-icon toggle-password fa-eye"></span>
                                    </span>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <button type="submit" class="btn btn-primary submit-btn btn-block">Login</button>
                              </div>
                              <div class="text-block text-center my-3">
                                <a href="<?php echo base_url('/login/forgot_password')?>" class="text-black text-small">Forgot Password</a>
                              </div>
                              <div class="text-block text-center my-3">
                                <span class="text-small font-weight-semibold">Not a member ?</span>
                                <a href="<?php echo base_url('/register')?>" class="text-black text-small">Create new account</a>
                              </div>
                            </form>
                          </div>
                          <ul class="auth-footer">
                            <li>
                              <a href="#">Conditions</a>
                            </li>
                            <li>
                              <a href="#">Help</a>
                            </li>
                            <li>
                              <a href="#">Terms</a>
                            </li>
                          </ul>
                          <p class="footer-text text-center"><span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2020 <a href="dmz.no" target="_blank">dmz.no</a>. All rights reserved.</span></p>
                        </div>
                      </div>
                    </div>
                    <!-- content-wrapper ends -->
                  </div>
                  <!-- page-body-wrapper ends -->
                </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </div>

        </div>
      </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="<?php echo base_url('assets/js/shared/jquery-1.12.2.min.js')?>"></script> 
  <script src="<?php echo base_url('assets/js/shared/slick.min.js')?>"></script>

  <script src="https://unpkg.com/@ungap/custom-elements-builtin"></script>
  <script src="<?php echo base_url('assets/js/x-frame-bypass.js'); ?>" type="module"></script>
<script src="<?php echo base_url('/assets/js/shared/toastr.js') ?>"></script>


<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
        <?php if(isset($loginpopup) && $loginpopup == true){ ?>
            jQuery("#popup_button").trigger("click");
      <?php } ?>

      var duration = '<?php echo implode(', ', $duration); ?>';
      var duration_array = duration.split(',');
      var $slideshow = $('.slidesss');
      var ImagePauses = duration_array;

      $(".slidesss").slick({
        initialSlide: 0,
        autoplay: false,
        dots: true,
        pauseOnDotsHover: false,
        pauseOnHover: false,
        autoplaySpeed: ImagePauses[0]
      });

      $slideshow.on('afterChange', function(event, slick, currentSlide) {
        if($('.pause_btn').css('display') == "block"){
          $slideshow.slick('slickSetOption', 'autoplaySpeed', ImagePauses[currentSlide], true);
        
          $(".pause_btn").css("display","block");
          $(".play_btn").css("display","none");
        }
      });

      // $('.slick-slider .slick-dots li').on('click', function() { 
      //   alert("hii");
      //   //$('.slidesss').slick('slickPause');
      // });

      $('.slidesss').slick('slickPlay');
       
      $('.pause_btn').on('click', function() {
        
        $('.slidesss')
          .slick('slickPause')
          .slick('slickSetOption', 'pauseOnDotsHover', true);
        $(".pause_btn").css("display","none");
        $(".play_btn").css("display","block");
      });

      $('.play_btn').on('click', function() {
        $('.slidesss')
          .slick('slickPlay')
          .slick('slickSetOption', 'pauseOnDotsHover', true);
        $(".pause_btn").css("display","block");
        $(".play_btn").css("display","none");
      }); 

    /*$(".slidesss").slick({
        autoplay: false,
        dots: true,
        pauseOnDotsHover: true,
    });*/
    
    
    
      // $(".slidesss").slick({
          // dots: true,
          // arrows: false,
          // infinite: true,
          // centerMode: false,
          // autoplay: true,
          // autoplaySpeed: <?php echo $posts->duration * 1000; ?>,
          // slidesToShow: 1,
          // slidesToScroll: 1,
            // responsive: [
              // {
                // breakpoint: 800,
                // settings: {
                  // arrows: false,
                  // centerMode: false,
                  // slidesToShow: 1
                // }
              // },
              // {
                // breakpoint: 414,
                // settings: {
                  // arrows: false,
                  // centerMode: false,
                  // slidesToShow: 1
                // }
              // }
          // ]
      // });
    });


  </script>
  <script type="text/javascript">
$(function() {
  $("#login_form").validate({
    rules: {
      email: {
        required: true
      },
      password: "required"
    },
    messages: {
     email: "Please enter a valid email or username.",
      password: "Please enter your password",
    },
    errorPlacement: function (error, element) {
      error.insertAfter($(element).parents('.input-group'));  
    },
    submitHandler: function(form) {
      var formdata = $(form).serialize();
      var action = $(form).attr('action');
      $("#pageloader").fadeIn();
      $.ajax({
        url:action,
        type:'POST',
        dataType: "JSON",
        data:formdata,
        success:function(data)
        {

          if(data.status)
          {
             toastr.success(data.message);
             window.location.reload();
             // setTimeout(function(){location.href = data.redirect; },3000);
          }
          else{
            toastr.error(data.message);
            $("#pageloader").fadeOut();
          }
        }
      })
    }
  });
});
//Password hide/show
$(".toggle-password").click(function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});


</script>
</body>
</html>
  