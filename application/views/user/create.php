<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="col-md-12">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="pl-3 pt-4 mb-0"><b>Add New User</b></h5>
            </div>
          </div>
          </div>
          <div class="card-body">
            <!-- <?php echo validation_errors(); ?> -->
            <form action="<?php echo base_url('User/store'); ?>" method="post" enctype="multipart/form-data" class="forms-sample mt-0" id="create_user">
              <div class="form-group">
                <label for="exampleInputName1">User Name</label>
                <input type="text" name="user_name" class="form-control" placeholder="User Name">
              </div>
              <div class="form-group">
                <label for="exampleInputName1">First Name</label>
                <input type="text" name="first_name" class="form-control" placeholder="First Name">
              </div>
              <div class="form-group">
                <label for="exampleInputName1">Last Name</label>
                <input type="text" name="last_name" class="form-control" placeholder="Last Name">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail3">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="Email">
              </div>
              <div class="form-group pass_sh">
                <label for="exampleInputEmail3">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="*******">
                <span toggle="#password" class="fa fa-fw field-icon toggle-password fa-eye"></span>
              </div>
              <div class="form-group">
                <label for="exampleInputCity1">User role</label>
                <select name="user_role" class="form-control form-control-lg">
                  <option value="">Please select a user role</option>
                  <option value="0">User</option>
                  <option value="1">Admin</option>
                  <option value="2">Super Admin</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword4">Image</label>
                <div class="row">
                  <div class="col-sm-8">
                    <input type="file" name="image" class="" id="exampleInputPassword4" placeholder="Password">
                  </div>
                  <div class="col-sm-4">
                    <img src="<?php echo base_url('assets/no_user.jpg') ?>" alt="User Avatar" class="img-thumbnail" style="height: 100px;width: 100%;object-fit: contain;">
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-success mr-2">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- content-wrapper ends -->
<script type="text/javascript">
$(function($) {
  $("#create_user").validate({
    rules: {
      user_name: "required",
      first_name: "required",
      last_name: "required",
      email: {
        required: true,
        email: true
      },
      password: "required",
      user_role: "required"
    },
    messages: {
      user_name: "Please enter your username",
      first_name: "Please enter your firstname",
      last_name: "Please enter your lastname",
      email: "Please enter your email",
      password: "Please enter your password",
      user_role: "Please enter your userrole"
    },
    submitHandler: function(form) {
      $("#pageloader").fadeIn();
      var formdata = $(form).serialize();
      var action = $(form).attr('action');
      $.ajax(
        {
          url: action,
          type: 'POST',
          dataType: "JSON",
          data: formdata ,
          success: function (data)
          {
            if(data.status)
            {
              toastr.success(data.message);
              setTimeout(function(){location.href=data.redirect; }, 3000);
            }
            else{
              toastr.error(data.message);
              $("#pageloader").fadeOut();
            }
          }
        });
    }
  });
});
</script>