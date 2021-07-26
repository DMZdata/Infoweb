<?php
$userdata = $this->session->userdata('user');
?>
<style type="text/css">
  .green{
    color: green;
  }
</style>
        <!-- partial -->
  <div class="main-panel">
          <div class="content-wrapper">
            <!-- Page Title Header Starts-->
            <div class="row page-title-header">
              <div class="col-12">
                <div class="page-header">
                  <h4 class="page-title">Dashboard</h4>
                  <div class="quick-link-wrapper w-100 d-md-flex flex-md-wrap">
                  </div>
                </div>
              </div>
            </div>
            <div class="row page-title-header">
              <?php if($userdata['user_role'] != 0){?>
               <div class="col-md-4 col-12 mb-3">
                <div class="card dash_content">
                  <a href="<?php echo base_url('user') ?>">
                    <div class="card-body">
                      <i class="fa fa-user-circle"></i>
                      <h4>Users : <span><?php echo $user_count; ?></span></h4>
                    </div>
                  </a>
                </div>
              </div> 
             <?php } ?>
             <div class="col-md-4 col-12 mb-3">
                <div class="card dash_content">
                  <a href="<?php echo base_url('screens') ?>">
                    <div class="card-body">
                      <i class="fa fa-window-maximize green"></i>
                      <h4>Online Screens : <span><?php echo $screen_count; ?></span></h4>
                    </div>
                  </a>
                </div>
              </div>
             <div class="col-md-4 col-12 mb-3">
                <div class="card dash_content">
                  <a href="<?php echo base_url('albums') ?>">
                    <div class="card-body">
                      <i class="fa fa-window-restore"></i>
                      <h4>All Screens : <span><?php echo $album_count; ?></span></h4>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-md-4 col-12 mb-3">
                <div class="card dash_content">
                  <a href="<?php echo base_url('posts') ?>">
                    <div class="card-body">
                      <i class="fa fa-photo"></i>
                      <h4>Posts : <span><?php echo $post_count; ?></span></h4>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="row page-title-header">
                          
          </div>
        </div>
          <!-- content-wrapper ends -->
<?php
//include('layouts/inc/footer.php');
?>       