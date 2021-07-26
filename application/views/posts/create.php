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
              <h5 class="mt-3 pl-3"><b>Add New Post</b></h5>
            </div>
          </div>
          </div>
          <div class="card-body">
            <form action="<?php echo base_url('posts/store'); ?>" onsubmit="return false" method="post" enctype="multipart/form-data" class="forms-sample" id="posts_form">
              <div class="container" align="center">
                <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="row">
                    <div class="image_area">                    
                        <label for="upload_image">
                          <img src="<?php echo base_url('assets/images/no_image.jpg') ?>" id="uploaded_image" class="img-responsive img-circle" />
                          <div class="overlay">
                            <div class="text">Click to Post Image</div>
                          </div>
                          <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                        </label>
                    </div>
                  </div>
                </div>
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Crop Image Before Upload</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="img-container">
                              <div class="row">
                                  <div class="col-md-8">
                                      <img src="" id="sample_image" />
                                  </div>
                                  <div class="col-md-4">
                                      <div class="preview"></div>
                                  </div>
                              </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" id="crop" class="btn btn-primary">Crop</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                  </div>
                  </div>      
                </div>
              </div>

              <div class="form-group">
                <label for="exampleInputEmail3">Post Title</label>
                <input type="text" name="title" class="form-control title" id="exampleInputEmail3" placeholder="Post Title">
                <small class="form-text error"></small>
              </div>
              <div class="form-group">
                <label for="Area">Content</label>
                <textarea name="content" class="form-control" id="exampleTextarea1"></textarea>    
                <small class="form-text error"></small>
              </div>
              <div class="form-group">
                <label for="Duration">Duration <small>( second per image )</small></label>
                <input type="text" name="duration" class="form-control duration" id="Duration" placeholder="Duration" value="1">
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
  
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script> -->
<!-- content-wrapper ends -->
<script type="text/javascript">
var pre_image = null;
var image = document.getElementById('sample_image');
var cropper;
jQuery(document).ready(function($){
  var $modal = jQuery('#modal');
  jQuery('#upload_image').change(function(event){
  	$this = this;
    var files = event.target.files;

    var done = function(url){
      image.src = url;
      $modal.modal('show');
    };

    if(files && files.length > 0)
    {
      reader = new FileReader();
      reader.onload = function(event)
      {
        var img_type = reader.result.split(';')[0].split('/')[1];
        console.log(img_type);
        if(img_type == 'webp' || img_type == 'jpeg' || img_type == 'jpg' || img_type == 'png'){
          	done(reader.result);
       		$this.value = '';
        }
        else{
          $this.value = '';
          alert('Select Only Png Or Jpg File.');
        }
      };
      reader.readAsDataURL(files[0]);
    }
  });

  $modal.on('shown.bs.modal', function() {
    cropper = new Cropper(image, {
      aspectRatio: 32/18,
      viewMode: 3,
      preview:'.preview'
    });

  }).on('hidden.bs.modal', function(){
    cropper.destroy();
      cropper = null;
  });

  jQuery('#crop').click(function(){
    canvas = cropper.getCroppedCanvas({
      width:800,
      height:800
    });

    canvas.toBlob(function(blob){
      url = URL.createObjectURL(blob);
      var reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function(){
         pre_image = reader.result;

          $('#uploaded_image').attr('src', pre_image);
          $modal.modal('hide');
      };
    });
  });

  $("#posts_form").validate({
    rules: {
      title: "required",
      content: "required",
      duration: "required",
      image: "required"
    },
    messages: {
      title: "Please enter your album title",
      content: "Please enter your Content",
      duration: "Please enter your duration",
      image: "Please enter your Image"
    },
    submitHandler: function(form) {
      if(pre_image == null){
        alert('Please select image');
        return false;
      }
      $("#pageloader").fadeIn();
      var formdata = new FormData(form);
      formdata.append('image', pre_image);
      $.ajax(
        {
          type:'POST',
          url: $(form).attr('action'),
          data:formdata,
          cache:false,
          contentType: false,
          processData: false,
          dataType: "JSON",
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
      return false;
    }
  });

});
</script>