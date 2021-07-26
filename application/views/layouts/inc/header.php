<?php 
$segment = $this->uri->segment(1);

$userdata = $this->session->userdata('user');
if($userdata == ''){
  redirect("login");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DMZ.NO-InfoWeb</title>
    <!-- plugins:css -->
    
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/iconfonts/ionicons/dist/css/ionicons.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/css/vendor.bundle.base.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendors/css/vendor.bundle.addons.css')?>">
    <!-- inject:css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/shared/style.css')?>">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/demo_1/style.css')?>">
    <!-- End Layout styles -->
    <!--link rel="shortcut icon" href="<?php //echo base_url('assets/images/favicon.ico')?>" /-->
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendors/iconfonts/font-awesome/css/font-awesome.min.css')?>" />
    <!-- toastr css -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/shared/toastr.css')?>">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>
    <!-- jquery -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
     <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     
    <script src="<?php echo base_url('/assets/js/shared/jquery-1.12.2.min.js')?>"></script>
    <script src="<?php echo base_url('/assets/js/shared/toastr.js') ?>"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    
  </head>
  <style type="text/css">
    .brand-logo-mini .first_letter{
      margin: 8px;
    }
    .nav-link.active::before{
      background: #fff !important;
    }
    .nav-item.active .nav-links{
      background: #0f25d5 !important;
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
      .copyLink{
        height: 26px;
      }
      .tooltip {
        position: relative;
        display: inline-block;
      }

      .tooltip .tooltiptext {
        visibility: hidden;
        width: 140px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -75px;
        opacity: 0;
        transition: opacity 0.3s;
      }

      .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
      }

      .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
      }
  </style>
  <body>
    <div class="container-scroller">
    <div id="pageloader">
       <img src="http://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif" alt="processing..." />
    </div>
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
          <span class="navbar-brand brand-logo">
            <h2 class="first_letter"><?php echo ($userdata['first_name'][0]);?></h2><?php echo $userdata['first_name']; ?></span>
          <span class="navbar-brand  brand-logo-mini">
            <h2 class="first_letter"><?php echo ($userdata['first_name'][0]);?></h2></span>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown d-xl-inline-block user-dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <?php if(!empty($userdata['image'])){ ?>
                <img class="img-xs rounded-circle" src="<?php echo base_url('assets/profile/'.$userdata['image'])?>" alt="Profile image">
                <?php }else{ ?>
                    <img src="<?php echo base_url('assets/no_user.jpg') ?>" alt="User Avatar" class="img-thumbnail" width="30">
                <?php } ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <?php if(!empty($userdata['image'])){ ?>
                  <img class="img-md rounded-circle" src="<?php echo base_url('assets/profile/'.$userdata['image'])?>" alt="Profile image">
                  <?php }else{ ?>
                    <img src="<?php echo base_url('assets/no_user.jpg') ?>" alt="User Avatar" class="img-thumbnail" width="30">
                <?php } ?>
                  <p class="mb-1 mt-3 font-weight-semibold"><?php echo $userdata['first_name'].' '.$userdata['last_name']; ?></p>
                  <p class="font-weight-light text-muted mb-0"><?php echo $userdata['email']; ?></p>
                </div>
                <a href="<?php echo base_url('user/profile') ?>" class="dropdown-item">My Profile<i class="dropdown-item-icon ti-dashboard"></i></a>
                <a href="<?php echo base_url('login/logout') ?>" class="dropdown-item">Sign Out<i class="dropdown-item-icon ti-power-off"></i></a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item <?php echo ($segment == 'dashboard') ? 'active':'' ?>">
              <a class="nav-link nav-links" href="<?php echo base_url('dashboard') ?>">
                <i class="menu-icon typcn typcn-document-text"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <?php
              if(in_array($userdata['user_role'], ['1','2'])){ ?>
                <li class="nav-item <?php echo ($segment == 'user') ? 'active':'' ?>">
                  <a class="nav-link nav-links" href="<?php echo base_url('user') ?>">
                    <span class="menu-title">Users</span>
                  </a>
                </li>
            <?php  }
             ?>
            
             <li class="nav-item <?php echo in_array($segment,['albums','posts']) ? 'active':'' ?>">
              <a class="nav-link nav-links" href="<?php echo base_url('screens') ?>">
                <i class="menu-icon typcn typcn-bell"></i>
                <span class="menu-title">My screens</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse <?php echo in_array($segment,['albums','posts']) ? 'show':'' ?>" id="auth">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item ">
                    <a class="nav-link <?php echo ($segment == 'screens') ? 'active':'' ?>" href="<?php echo base_url('screens') ?>">
                    <i class="menu-icon typcn typcn-bell"></i>
                    <span class="menu-title">Online screens</span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link <?php echo ($segment == 'albums') ? 'active':'' ?>" href="<?php echo base_url('albums') ?>">
                    <i class="menu-icon typcn typcn-bell"></i>
                    <span class="menu-title">My screens</span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link <?php echo ($segment == 'posts') ? 'active':'' ?>" href="<?php echo base_url('posts') ?>">
                    <i class="menu-icon typcn typcn-bell"></i>
                    <span class="menu-title">My Posts</span>
                  </a>
                </li>
                </ul>
              </div>
            </li>



           
          </ul>
        </nav>