 <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid clearfix">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2020 <a href="dmz.no" target="_blank">dmz.no</a>. All rights reserved.</span>
              </span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    
    <!-- plugins:js -->
    <script src="<?php echo base_url('assets/vendors/js/vendor.bundle.base.js')?>"></script>
    <script src="<?php echo base_url('assets/vendors/js/vendor.bundle.addons.js')?>"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="<?php echo base_url('assets/js/shared/off-canvas.js')?>"></script>
    <script src="<?php echo base_url('assets/js/shared/misc.js')?>"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="<?php echo base_url('assets/js/demo_1/dashboard.js')?>"></script>
    <script src="<?php echo base_url('assets/js/shared/dropzone.js')?>"></script>
    <!-- End custom js for this page-->
    <!-- Data Table -->
    <script type="text/javascript" src="<?php echo base_url('/assets/js/shared/jquery.dataTables.min.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/assets/js/shared/dataTables.bootstrap4.min.js')?>"></script>
    <!-- Toastr -->
    <script src="<?php echo base_url('/assets/js/shared/toastr.js') ?>"></script>
    <script type="text/javascript">
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
    
    //Post DataTable
    
   

    // $('#UserDropdown').click(function (e) {

    //     // Used to stop the event bubbling..
    //     e.stopPropagation()
    //     $(".dropdown-menu-right").show();
    // });

    // // Hide the "info_image_click" by clicking outside container
    // $(document).click(function () {
    //     $(".dropdown-menu-right").hide();
    // });

// Multiple Media Add
    $(document).ready(function() {
      var max_fields_limit      = 10; //set limit for maximum input fields
      var x = 1; //initialize counter for text box
      $('.add_more_button').click(function(e){ //click event on add more fields button having class add_more_button
          e.preventDefault();
         
          if(x < max_fields_limit){ //check conditions
              x++; //counter increment
              $('.add_option').append('<div class="row col-sm-12">'
                +'<div class="col-sm-4">'
                +'<div class="form-group">'
                +'<input type="text" name="caption[]" class="form-control" placeholder="Enter Content">'
                +'</div></div>'
                +'<div class="col-sm-4">'
                +'<div class="form-group">'
                +'<input type="file" name="image[]" class="form-control" placeholder="Enter Content">'
                +'</div></div>'
                +'<div class="col-sm-2"><div class="form-group"><button class="btn btn-sm btn-primary remove_option">Remove</button></div></div>'
                +'</div></div>'); //add input field
          }
      }); 
    });
// Remove Media
  $(document).on("click", ".remove_option", function(e) {
    e.preventDefault(); 
    if(confirm("Do you want to delete this slider?")){
    var remove = $(this);
    var id = $(this).data("id");
    $.ajax(
        {
            url: "<?php echo base_url('Posts/media_delete') ?>",
            type: 'POST',
            dataType: "JSON",
            data: {
                "id": id
            },
            success: function (data)
            {
              if(data.status)
                {
                  remove.parent().parent().remove();
                }
                else{
                  
                }
            }
        });
    }
  });
  
  </script>
  <!-- <script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
<script>
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone('#myDropzone', {
  url: '<?php echo base_url('posts/store'); ?>',
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 5,
    maxFiles: 5,
    maxFilesize: 1,
    acceptedFiles: 'image/*',
    //addRemoveLinks: true,
    // previewTemplate: "<input type='text' name='caption'/>"
        previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n <input type='text' name='caption[]' class='caption'/>  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  </div>",

        init: function() {
         dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

        // for Dropzone to process the queue (instead of default form behavior):
        document.getElementById("submit-all").addEventListener("click", function(e) {
            // Make sure that the form isn't actually being sent.
            e.preventDefault();
            e.stopPropagation();
            dzClosure.processQueue();
        });

//         //send all the form data along with the files:
        this.on("sendingmultiple", function(data, xhr, formData) {
          var data = $('form').serializeArray();
          $.each(data, function( index, value ) {
            // formData.append('date', jQuery(".date").val());
            // formData.append('time', jQuery(".time").val());
            // formData.append('title', jQuery(".title").val());
            // formData.append('post_option', jQuery(".post_option").val());
            // formData.append('duration', jQuery(".duration").val());
            // formData.append('area', jQuery(".area").val());


            formData.append("caption[]", jQuery(".caption").eq(index).val());
          });
        });
   }

});

$(document).on("submit", "#posts_form", function(e) {
    e.preventDefault(); 
    myDropzone.processQueue();
    var formdata = $(this).serialize();
    var action = $(this).attr('action');
    $.ajax(
        {
            url: action,
            type: 'POST',
            dataType: "JSON",
            data: formdata ,
            success: function (data)
            {
              // if(data.status)
              //   {
              //     remove.parent().parent().parent().remove();
              //   }
              //   else{
                  
              //   }
            }
        });
  });
</script>    -->

  </body>
</html>