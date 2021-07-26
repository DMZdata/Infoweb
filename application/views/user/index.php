<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="col-md-12">
            <div class="row">
              <div class="col-sm-6 col-6">
                <h5 class="mt-3 pl-3"><b>User List</b></h5>
              </div>
              <div class="col-sm-6 col-6">
                <div class=" float-sm-right pr-3 mt-3">
                  <a href="<?php echo base_url('user/create') ?>" class="btn btn-primary btn-fw pull-right"><i class="fa fa-plus"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table" id="user">
                <thead>
                  <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>User Role</th>
                    <th>Action</th>
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
//User DataTable
$(document).ready(function () {
  var table = $('#user').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax":{
             "url": "<?php echo base_url('user/datatable') ?>",
             "dataType": "json",
             "type": "POST",
             "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
            },
      "columns": [
              { "data": "user_name" },
              { "data": "email" },
              { "data": "user_role" },
              { "data": "action" },
           ]   
  });
  $(document).on("click", ".remove_user", function(e) {
    e.preventDefault(); 
    if(confirm("Do you want to delete this user?")){
    var remove = $(this);
    var id = $(this).data("id");
    
    $.ajax(
        {
          url: "<?php echo base_url('user/destroy/') ?>"+id,
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
});
</script>
  