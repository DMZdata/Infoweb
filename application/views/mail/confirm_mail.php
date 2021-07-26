<!DOCTYPE HTML>
<html>
<head>
<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet" />
<style type="text/css">
.btn {
    font-size: 14px;
    line-height: 1;
    font-family: "roboto",sans-serif;
    color: #fff !important;
    background-color: #2196f3;
    border-color: #2196f3;
    padding: 10px 25px;
    border-radius: 5px;
    min-width: 120px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}
</style>
</head>
<body> 
<div class="row" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            </div> 
            <div class="modal-body">
                <a href="<?php echo base_url('/login/confirm_password/'.$id); ?>" class="btn">Create New Password</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>