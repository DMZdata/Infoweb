<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DMZ.NO-InfoWeb</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/css/vendor.bundle.base.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/css/vendor.bundle.addons.css')?>">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/shared/style.css')?>">
    <!--<link rel="shortcut icon" href="<?php echo base_url('/assets/images/favicon.ico')?>" />-->
    <!-- toastr css -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/shared/toastr.css')?>">
    <!-- font awsome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/iconfonts/font-awesome/css/font-awesome.min.css')?>" />
    <style type="text/css">
      .title{
        color: #fff;
      }
      #pageloader
      {
        background: rgba( 255, 255, 255, 0.4 );
        display: none;
        height: 100%;
        position: fixed;
        width: 100%;
        z-index: 9999;
      }

      #pageloader img
      {
        left: 50%;
        margin-left: -32px;
        margin-top: -32px;
        position: absolute;
        top: 50%;
      }
    </style>
  </head>
  <body>
    <div id="pageloader">
       <img src="http://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif" alt="processing..." />
    </div>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="w-100">
            <div class="col-lg-4 col-sm-12 col-md-6 mx-auto">
              <h3 class="title text-center mb-4">Forgot Password</h3>
              <div class="auto-form-wrapper">  
                <form action="<?php echo base_url('login/forgot_pass_form') ?>" method="post" id="forgot_form">
                  <div class="form-group">
                    <label class="label">Email *</label>
                    <div class="input-group">
                      <input type="text" name="email" class="form-control" placeholder="Email" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary submit-btn btn-block">Submit</button>
                  </div>
                  <div class="text-block text-center my-3">
                    <span class="text-small font-weight-semibold">Already have and account ?</span>
                    <a href="<?php echo base_url('login') ?>" class="text-black text-small">Login</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
<!-- container-scroller -->
   
<!--toastr js  -->
<script src="<?php echo base_url('assets/js/shared/jquery-1.12.2.min.js')?>"></script>
<script src="<?php echo base_url('/assets/js/shared/toastr.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function() {
  $("#forgot_form").validate({
    rules: {
      email: {
        required: true,
        email: true
      }
    },
    messages: {
     email: "Please enter your email.",
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
             // $("#pageloader").fadeOut();
             setTimeout(function(){location.href=data.redirect; },3000);
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
</script>
  </body>
</html>
    