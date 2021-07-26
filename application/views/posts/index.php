<!-- partial -->
<style>
  .dropbtn {
    background-color: #2196f3;
    color: white;
    
  }

  .dropdown {
    position: relative;
    display: inline-block;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    margin-top: 25%;
  }

  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  .dropdown-content a:hover {background-color: #ddd;}

  .dropdown:hover .dropdown-content {display: block;}

/*.dropdown:hover .dropbtn {background-color: #3e8e41;}*/
</style>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="col-md-12">
            <div class="row">
              <div class="col-sm-6 col-6">
                <h5 class="mt-3 pl-3"><b>All Posts</b></h5>
              </div>
              <div class="col-sm-6 col-6">
                <div class=" float-sm-right pr-3 mt-3">
                  <div class="dropdown">
                    <button class="dropbtn btn btn-primary btn-fw pull-right"><i class="fa fa-plus"></i></button>
                    <div class="dropdown-content">
                      <a href="<?php echo base_url('posts/create') ?>" >Post with Image</a>
                      <a href="<?php echo base_url('posts/create_pdf') ?>">Post with Pdf</a>
                      <a href="<?php echo base_url('posts/create_iframe') ?>">Post with iframe</a>
                      
                    </div>
                  </div>
                 
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table" id="posts">
                <thead>
                  <tr>
                    <th>TITLE</th>
                    <!--th>LINKS</th-->
                    <th>Description</th>
                    <th>TYPE</th>
                    <th>ACTION</th>
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
       var table = $('#posts').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                   "url": "<?php echo base_url('posts/datatable') ?>",
                   "dataType": "json",
                   "type": "POST",
                   "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
                  },
            "columns": [
                    { "data": "title" },
                    //{ "data": "links" },
                    { "data": "content" },
                    { "data": "image" },
                    { "data": "action","orderable": false },
            ], 
            "language": {
                "processing": '<i class="fa fa-spinner" aria-hidden="true"></i>'
            },  
      });

      table.on( 'draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
      } );

      $(document).on("click", ".remove_post", function(e) {
        e.preventDefault(); 
        if(confirm("Do you want to delete this post?")){
        var remove = $(this);
        var id = $(this).data("id");
        
        $.ajax(
            {
                url: "<?php echo base_url('posts/destroy') ?>",
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
    //Remove Post
    function copyToLink(element) {
      console.log(element);
       var $temp = $("<input>");
       $("body").append($temp);
       $temp.val(element).select();
       document.execCommand("copy");
       $temp.remove();
    }

  </script>