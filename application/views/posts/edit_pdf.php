<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
<script src="https://unpkg.com/cropperjs"></script>
<style type="text/css">
  .dropzone {
      border: 2px dashed #0087F7;
      border-radius: 5px;
  }
  .dropzone .dz-message {
      margin: 2em auto !important;
  }
  img {
        display: block;
        max-width: 100%;
    }

    .preview {
        overflow: hidden;
        width: 160px; 
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }

    .modal-lg{
        max-width: 1000px !important;
    }

    .overlay {
      position: absolute;
      bottom: 10px;
      left: 0;
      right: 0;
      background-color: rgba(255, 255, 255, 0.5);
      overflow: hidden;
      height: 0;
      transition: .5s ease;
      width: 100%;
    }

    .image_area:hover .overlay {
      height: 50%;
      cursor: pointer;
    }

    .text {
      color: #333;
      font-size: 20px;
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
      text-align: center;
    }
    .image_area{
      width: 100%;
      border: 2px dashed;
    }
    .image_area label{
      margin-bottom: 0px;
      height: 350px;
      width: 100%;
    }
    .image_area label img{
      height: 100%;
    }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="card">
          <div class="col-md-12">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="mt-3 pl-3"><b>Edit Post</b></h5>
            </div>
          </div>
          </div>
          <div class="card-body">
            <div class="col-md-12">
            <form id="posts_form" method="post"  enctype="multipart/form-data">
              <input type="hidden" id="id" name="id" value="<?php echo $post->id; ?>"  class="form-control document"/>
              <div class="container" >
                <div class="form-group">
                  <label for="exampleInputEmail3">Select file to upload</label>
                   <input type="file" id="document" name="document"  class="form-control document"/>
                    <small class="form-text error"></small>
                   
                    <div id="preview">
                       <?php 
                      // var_dump($post);die;
                      $pdfchunks = json_decode($post->image);

                      foreach ($pdfchunks as $pdf) {
                        echo '<embed width="100" height="130" style="padding-right: 1%;" name="plugin" src="'.base_url('assets/upload_pdf/pdf_file/split/').$pdf.'"  type="application/pdf" >';
                        
                      }
                      ?>
                    </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Post Title</label>
                  <input type="text" name="title" id="title" class="form-control title" id="exampleInputEmail3" placeholder="Post Title" value="<?php echo $post->title; ?>">
                  <small class="form-text error"></small>
                </div>
                <div class="form-group">
                  <label for="Area">Content</label>
                  <textarea name="content" class="form-control" id="content"><?php echo $post->content; ?></textarea>    
                  <small class="form-text error"></small>
                </div>
                <div class="form-group">
                  <label for="Duration">Duration <small>( second per image )</small></label>
                  <input type="text" name="duration" class="form-control duration" id="Duration" value="<?php echo isset($post->duration)? $post->duration : 1 ; ?>" placeholder="Duration">
                  <small class="form-text error"></small>
                </div>
                <div class="row add_option"></div>
                <button type="submit" class="btn btn-success mr-2 mt-2" id="submit-all">Submit</button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('assets/js/shared/jquery-1.12.2.min.js')?>"></script>
<script src="<?php echo base_url('/assets/js/shared/toastr.js') ?>"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>  -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script> -->
<!-- content-wrapper ends -->
<script type="text/javascript">
  jQuery('#document').change(function(event){
    // alert("jhjh");
    files = this.files;
    size = files[0].size;
    //max size 50kb => 50*1000

    if( size > 2000282){
      jQuery(this).val('');
       toastr.error("Please upload less than 2mb file");
       return false;
    }else{
      var formdata = new FormData();
      var pre_image = $('#document').prop('files')[0];   
      formdata.append('image', pre_image);
      $("#preview").html("");
      $.ajax(
        {
          type:'POST',
          url:'<?php echo base_url('posts/pdf_read'); ?>',
          data:formdata,
          cache:false,
          contentType: false,
          processData: false,
          dataType: "JSON",
          success: function (data)
          {
            if(data == false){

            }else{
              var files = '';
              $.each(data, function(i, item) {
                var extension = item.substr( (item.lastIndexOf('.') +1) );
              
                if(extension){
                  var base_url = "<?php echo base_url('assets/upload_pdf/split/'); ?>";
                  files += '<embed width="100" height="130" style="padding-right: 1%;" name="plugin" src="'+base_url+item+'"  type="application/pdf" data-file="'+item+'">';
                }
              });
              $("#preview").html(files);
            }
          }
        });
    }

  });


  

 

  
   $('#posts_form').on('submit', function (e) {
      // alert("hii");
      e.preventDefault();
      
      $("#pageloader").fadeIn();
      var formdata = new FormData();
      var pre_image = $('#document').prop('files')[0]; 


      formdata.append('document', pre_image);
      formdata.append('id', $('#id').val());
      formdata.append('title', $('#title').val());
      formdata.append('content', $('#content').val());
      formdata.append('duration', $('#Duration').val());

      $.ajax(
        {
          type:'POST',
          url: '<?= base_url('posts/update1')?>',
          data:formdata,
          cache:false,
          contentType: false,
          processData: false,
          dataType: "JSON",
          success: function (data)
          {
            $("#pageloader").fadeOut();
            if(data.status)
            {
              
              toastr.success(data.message);
              setTimeout(function(){ location.href = data.redirect; }, 3000);
            }
            else{
              toastr.error(data.message);
              $("#pageloader").fadeOut();
            }
          }
        });
      return false;
    });
  // });

// });
</script>