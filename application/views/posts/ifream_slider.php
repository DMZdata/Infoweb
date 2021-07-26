<?php
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/> -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/shared/slick.css')?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/shared/slick-theme.css')?>"/>
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
     position: fixed;
    left: 0;
    width: 100%;
    padding: 10px;
    text-align: center;
    z-index: 9999999;
    top: 30px;
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
    height: 100%;
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
  .slick-track{
    height: 100vh;
  }
  .slick-dots li button::before {
    font-size: 0px;
  }


  .top-bar {
    background: rgb(237, 237, 237, 0.6);
    color: #000000;
    padding: 1%;
  }

  
</style>
</head>
<body>
  
  <div class="banner_slide">
    <div class="body_area image fullpane canvas 123">
      <div class="slidesss">
        <?php 
         
          foreach ($slider as $key => $media) { 
            // var_dump($media);die;
          if($media->type == "pdf"){  
            $pdfpages = json_decode($media->image);
            foreach ($pdfpages as $pdfpage) {
            ?>
              <div class="covrimage">
                <div class="bgimagee">
                  <center>
                    <embed width="400" height="600" name="plugin" src="<?php echo base_url('assets/upload_pdf/pdf_file/split/'.$pdfpage) ?>" class="active" type="application/pdf">
                  </center>
                  <div class="caption"><div><?php echo $media->content; ?></div></div>
                </div>
              </div>
            <?php 
            }
          }else if($media->type == "image"){
            // $type = $media->type;
            // if($type=='image'){
             ?>
              <div class="covrimage">
                  <div class="bgimagee" style="background-image:url(<?php echo base_url('assets/upload/'.$media->image) ?>)">
                    <div class="caption"><div><?php echo $media->content; ?></div></div>
                  </div>
              </div>
            <?php 
            // } 
          }else if($media->type == "iframe"){ ?>
               <div class="covrimage">
                  <div class="bgimagee" >
                    <center>
                      <iframe is="x-frame-bypass" src="<?php echo $media->image; ?>" style="width: 100%;height: 650px;"></iframe>
                    </center>
                    <div class="caption"><div><?php echo $media->content; ?></div></div>
                  </div>
              </div>
          
          <?php
          } 
        } ?>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="<?php echo base_url('assets/js/shared/jquery-1.12.2.min.js')?>"></script> 
  <script src="<?php echo base_url('assets/js/shared/slick.min.js')?>"></script>

  <script src="https://unpkg.com/@ungap/custom-elements-builtin"></script>
  <script src="<?php echo base_url('assets/js/x-frame-bypass.js'); ?>" type="module"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      $(".slidesss").slick({
          dots: true,
          arrows: false,
          infinite: true,
          centerMode: false,
          autoplay: true,
          slidesToShow: 1,
          slidesToScroll: 1,
            responsive: [
              {
                breakpoint: 800,
                settings: {
                  arrows: false,
                  centerMode: false,
                  slidesToShow: 1
                }
              },
              {
                breakpoint: 414,
                settings: {
                  arrows: false,
                  centerMode: false,
                  slidesToShow: 1
                }
              }
          ]
      });
    })
  </script>

</body>
</html>
  