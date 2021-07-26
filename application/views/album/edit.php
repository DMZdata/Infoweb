<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css'>
<style type="text/css">
form {
  margin-top: 20px;
}
select {
  width: 100%;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
  padding: 5px;
  position: relative;
  background-color: #fff;
  margin: 15px 8px 5px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
    border: 1px solid red;
    border-radius: 50%;
    width: 15px;
    text-align: center;
    color: #fff !important;
    background-color: red;
    line-height: 13px;
    position: absolute;
    right: -8px;
    top: -8px;
    font-size: 16px;
    height: 15px;
    display: inline-block;
    margin-right: 0;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice span img{
  display: block;
  margin: 0 auto 5px;
}
.post_title {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 60px;
}
</style>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="col-md-12">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="pl-3 pt-4 mb-0"><b>Edit Screen</b></h5>
            </div>
          </div>
          </div>
          <div class="card-body">
            <div id="output"></div>
            <form action="<?php echo base_url('albums/update'); ?>" method="post" enctype="multipart/form-data" class="forms-sample mt-0" id="update_albums">
              <input type="hidden" name="id" value="<?php echo $albums->id; ?>">
              <input type="hidden" name="postids" value="<?php echo $albums->post_ids; ?>">
              <div class="form-group">
                <label for="exampleInputCity1">Posts</label>                  
                <select data-placeholder="Choose posts ..." name="post_ids[]" multiple="multiple" class="chosen-select mySelect for">
                <?php
                 $post_ids = (explode(',',$albums->post_ids));
                 foreach ($posts as $key => $value) { 
                  if($value->type == "image"){
                    $image = 'picture-o';//'<i class="fa fa-picture-o" title="Image"></i>';//base_url().'assets/upload/'.$value->image;
                  }else if($value->type == "iframe"){
                    $image = 'globe';//'<i class="fa fa-globe" title="I-frame"></i>';//base_url('assets/upload/iframe-icon.png');
                  }else{
                    $image = 'file-pdf-o';//'<i class="fa fa-file-pdf-o" title="PDF"></i>';//base_url('assets/upload/pdf-icon.png');
                  }
                  ?>
                  <option value="<?php echo $value->id; ?>"<?php echo in_array($value->id,$post_ids) ? 'selected':'' ?> data-image="<?=$image?>"><?php echo $value->title; ?></option>
                <?php } ?>
              </select>
                <small class="form-text error"></small>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail3">Screen Title</label>
                <input type="text" name="title" class="form-control title" id="exampleInputEmail3" value="<?php echo $albums->title; ?>" placeholder="Album Title">
                <small class="form-text error"></small>
              </div>
              <div class="form-group" style="display:none">
                <label for="exampleInputEmail3">Post Type</label>
                <select name="type" class="form-control form-control-lg">
                  <option value="image">Image</option>
                </select>
                <small class="form-text error"></small>
              </div>
              <!--div class="form-group">
				<div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input type="checkbox" name="show_title" value="1" class="form-check-input" <?php echo $albums->show_title == 1 ? 'checked':'' ?>> Show Title? <i class="input-helper"></i></label>
                </div>
                <small class="form-text error"></small>
              </div-->
              <!-- <div class="form-group">
                <label for="Duration">Duration <small>( second per image )</small></label>
                <input type="text" name="duration" class="form-control duration" id="Duration" value="<?php echo $albums->duration; ?>" placeholder="Duration">
                <small class="form-text error"></small>
              </div> -->
              <div class="form-group">
				<div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input type="checkbox" name="show_title" value="1" class="form-check-input" <?php echo $albums->show_title == 1 ? 'checked':'' ?>> Show Title</label>
                </div>
              </div>
              <div class="form-group">
				<div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input type="checkbox" name="show_date_time" value="1" class="form-check-input" <?php echo $albums->show_date_time == 1 ? 'checked':'' ?>> Show Date/Time</label>
                </div>
              </div>
              <div class="form-group" style="display:none">
				<div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input type="checkbox" name="post_option" value="1" class="form-check-input" <?php echo $albums->post_option == 1 ? 'checked':'' ?> checked> Fill Post - crop image <i class="input-helper"></i></label>
                </div>
              </div>
              <!--div class="form-group">
                <label for="exampleInputCity1">Display Option</label>
                <select name="post_option" class="form-control form-control-lg post_option">
                  <option value="">Select Option</option>
                  <option value="0" <?php echo $albums->post_option == 0 ? 'selected':'' ?>>Fill Post - crop image</option>
                </select>
                <small class="form-text error"></small>
              </div-->
              <div class="form-group" style="display:none">
                <label for="Area">Area</label>
                <input type="text" name="area" class="form-control area" id="Area" value="Area" placeholder="Area">
                <small class="form-text error"></small>
              </div>
              <div class="form-group">
				<div class="form-check form-check-flat">
					<label class="form-check-label">
                    <input type="checkbox" name="active" value="1" class="form-check-input" <?php echo $albums->active == 1 ? 'checked':'' ?>>Private<i class="input-helper"></i></label>
                </div>
              </div>
              <button type="submit" class="btn btn-success mr-2" id="submit-all">Update</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js'></script>
<!-- content-wrapper ends -->

<!-- content-wrapper ends -->
<script type="text/javascript">
$(function($) {
  $("#update_albums").validate({
    rules: {
      title: "required",
      post_option: "required",
      /*duration: "required",*/
      area: "required"
    },
    messages: {
      title: "Please enter your album title",
      post_option: "Please select the display option",
      /*duration: "Please enter your duration",*/
      area: "Please enter your area"
    },
    submitHandler: function(form) {
      $("#pageloader").fadeIn();
      var formdata = new FormData(form);
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
    }
  });
});
//Select Posts
// $(".mySelect").select2({
//     placeholder: "select",
//     allowClear: false,
//     minimumResultsForSearch: 5
// });

$('.mySelect').select2({
    templateResult: formatState,
    templateSelection: formatState,
    minimumResultsForSearch: 5,
    placeholder: 'Select a month'
}).on("select2:select", function (evt) {
    var id = evt.params.data.id;
    var element = $(this).children("option[value="+id+"]");
    moveElementToEndOfParent(element);
    $(this).trigger("change");
});


var ele = $(".mySelect").parent().find("ul.select2-selection__rendered");
ele.sortable({
    containment: 'parent',
    update: function() {
        orderSortedValues();
        console.log(""+$(".mySelect").val())
    }
});

orderSortedValues = function() {
  $(".mySelect").parent().find("ul.select2-selection__rendered").children("li[title]").each(function(i, obj){
    var element = $(".mySelect").children('option').filter(function () { return $(this).html() == obj.title });
    moveElementToEndOfParent(element);
  });
};

moveElementToEndOfParent = function(element) {
  var parent = element.parent();
  element.detach();
  parent.append(element);
  $('span.select2-selection__choice__remove').each(function(){$(this).attr('title','Delete');});  
};

function formatState (opt) {
    if (!opt.id) {
        return opt.text.toUpperCase();
    } 
    var optimage = $(opt.element).attr('data-image');
	console.log(optimage);
    if(!optimage){
       return opt.text.toUpperCase();
    } else {                    
        var $opt = $('<span class="wrapper-parent-class"><i class="fa fa-' + optimage + '" title="' + opt.text.toUpperCase() + '"></i><div class="post_title">' + opt.text.toUpperCase() + '</div></span>');
        return $opt;
    }
};

var orderArray = $("input[name=postids]").val().split(',');
var listArray = $('.mySelect option');
var shortlistArray = $('.ui-sortable li');

for (var i = 0; i < orderArray.length; i++) {
   $('.mySelect').append(listArray[orderArray[i]-1]);
}
for (var i = 0; i < orderArray.length; i++) {
   $('.ui-sortable').append(shortlistArray[orderArray[i]-1]);
}

setTimeout(function(){$('span.select2-selection__choice__remove').each(function(){$(this).attr('title','Delete');});}, 100);
</script>