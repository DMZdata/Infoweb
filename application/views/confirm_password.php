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
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="w-100">
            <div class="col-lg-4 col-sm-12 col-md-6 mx-auto">
              <div class="auto-form-wrapper">
                <?php if(isset($user->verify_id)) { ?>
                <form action="<?php echo base_url('login/confirm_pass_form') ?>" method="post" id="forgot_form">
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
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
                    <div class="input-group">
                      <input type="password" name="confirm_pass" class="form-control" id="confirm_pass" placeholder="*********">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <span toggle="#confirm_pass" class="fa fa-fw field-icon toggle-password fa-eye"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary submit-btn btn-block">Update Password</button>
                  </div>
                </form>
                <?php }else{ ?>  
                  <p class="mb-4" align="center">Link Is Expired.</p>
                <?php } ?>
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
      password: {
        required: true
      },
      confirm_pass: {
        required: true,
        equalTo: '[name="password"]'
      }
    },
    messages: {
     password: "Please enter your password.",
     confirm_pass: {
        required: "Please enter your confirm password",
        equalTo: "Password not match"
      },
    },
    errorPlacement: function (error, element) {
      error.insertAfter($(element).parents('.input-group'));  
    },
    submitHandler: function(form) {
      var formdata = $(form).serialize();
      var action = $(form).attr('action');
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
             setTimeout(function(){location.href=data.redirect; },3000);
          }
          else{
            toastr.error(data.message);
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
    