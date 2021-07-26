<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="col-md-12">
            <div class="row">
              <div class="col-sm-6">
                <h5 class="mt-3 pl-3"><b>All Online Screens</b></h5>
              </div>
              <div class="col-sm-6">
                <div class=" float-sm-right pr-3 mt-3">
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table" id="screens">
                <thead>
                  <tr>
                    <th>TITLE</th>
                    <th>URL</th>
                    <!--th>TYPE</th-->
                    <th>CHANGED</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->
  <script type="text/javascript">
$(document).ready(function () {
   var table = $('#screens').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
               "url": "<?php echo base_url('screens/datatable') ?>",
               "dataType": "json",
               "type": "POST",
               "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
              },
        "columns": [
                { "data": "title" },
                { "data": "links" },
                //{ "data": "type" },
                { "data": "created" },
                { "data": "status" },
             ]  ,
             "language": {
            "processing": '<i class="fa fa-spinner" aria-hidden="true"></i>'
        },  
  });

  table.on( 'draw', function () {
    $('[data-toggle="tooltip"]').tooltip();
  } );

  $(document).on("click", ".remove_album", function(e) {
    e.preventDefault(); 
    if(confirm("Do you want to delete this Albums?")){
    var remove = $(this);
    var id = $(this).data("id");
    $.ajax(
        {
            url: "<?php echo base_url('albums/destroy') ?>",
            type: 'POST',
            dataType: "JSON",
            data: {
                "id": id
            },
            success: function (data)
            {
              if(data.status)
                {
                  table.ajax.reload();
                  toastr.success(data.message);
                  remove.parent().parent().remove();
                }
                else{
                  
                }
            }
        });
    }
  });

  function heartbeat() {
      clearTimeout(window.intervalID);
      table.ajax.reload(null, false);
      window.intervalID = setTimeout(function() {
          heartbeat();    
      }, 8000); 
  }
  heartbeat();

});

function copyToLink(element) {
   var $temp = $("<input>");
   $("body").append($temp);
   $temp.val(element).select();
   document.execCommand("copy");
   $temp.remove();
}
</script>