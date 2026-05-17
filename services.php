<?php require_once('session.php'); 
$userRole = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : 'admin';
if($userRole != 'admin'){
    header('Location: cashier_home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manage Services — HypeLaundry</title>
    <meta name="description" content="Manage Laundry Services">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link href="assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-theme.css">
  </head>
  <body class="hold-transition skin-blue sidebar-mini modern-theme">
    <div class="wrapper">

      <header class="main-header">
        <a href="home.php" class="logo">
          <span class="logo-mini"><b>H</b>L</span>
          <span class="logo-lg">📦 <b>Hype</b>Laundry</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
        </nav>
      </header>

      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu">
          <?php include_once('navigation.php'); ?>
          </ul>
        </section>
      </aside>

      <div class="content-wrapper">
        <section class="content-header">
          <div class="welcome-section">
            <div class="greeting">System Setup</div>
            <div class="page-title">Laundry Services</div>
          </div>
        </section>

        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-tags" style="margin-right:8px; color:#818cf8;"></i>Manage Services</h3>
                </div>
                <div class="box-body">
                  <form id="form-add-service" class="form-inline" style="margin-bottom: 20px;">
                    <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Service Name" required style="min-width:250px;">
                    <input type="number" step="0.01" class="form-control" id="service_price" name="price" placeholder="Price (₱)" required style="width:120px;">
                    <button type="submit" class="btn btn-primary">Add Service</button>
                  </form>
                  <div id="table-services"></div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Edit Modal -->
      <div class="modal fade" id="modal-edit-service" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content" style="background: #1e293b; color: #f1f5f9; border: 1px solid rgba(148,163,184,0.1); border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(148,163,184,0.1);">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" style="font-weight:700;">Edit Service</h4>
            </div>
            <div class="modal-body">
              <form id="form-edit-service">
                <input type="hidden" id="edit_service_id" name="id">
                <div class="form-group">
                  <label style="color:#94a3b8; font-size:12px; font-weight:600;">Service Name</label>
                  <input type="text" class="form-control" id="edit_service_name" name="service_name" required style="background:#0f172a; border:1px solid rgba(148,163,184,0.2); color:#f1f5f9;">
                </div>
                <div class="form-group">
                  <label style="color:#94a3b8; font-size:12px; font-weight:600;">Price (₱)</label>
                  <input type="number" step="0.01" class="form-control" id="edit_service_price" name="price" required style="background:#0f172a; border:1px solid rgba(148,163,184,0.2); color:#f1f5f9;">
                </div>
                <div style="text-align:right;">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 4.0
        </div>
        <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> Sales & Inventory Management System.
      </footer>
    </div>

    <?php include_once('modal/msg.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('script.php'); ?>
    
    <script>
        $(document).ready(function() {
            load_services();
        });

        function load_services(){
            $.ajax({
                url: 'data/all_services.php',
                type: 'post',
                success: function (data) {
                    $('#table-services').html(data);
                }
            });
        }

        $('#form-add-service').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'data/insert_service.php',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data){
                    if(data.valid){
                        $('#service_name').val('');
                        $('#service_price').val('');
                        load_services();
                    } else {
                        alert(data.msg || 'Failed to add service.');
                    }
                }
            });
        });

        function editService(id, name, price){
            $('#edit_service_id').val(id);
            $('#edit_service_name').val(name);
            $('#edit_service_price').val(price);
            $('#modal-edit-service').modal('show');
        }

        $('#form-edit-service').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'data/edit_service.php',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data){
                    if(data.valid){
                        $('#modal-edit-service').modal('hide');
                        load_services();
                    } else {
                        alert(data.msg || 'Failed to update service.');
                    }
                }
            });
        });

        function deleteService(id){
            if(confirm('Are you sure you want to delete this service?')){
                $.ajax({
                    url: 'data/delete_service.php',
                    type: 'post',
                    dataType: 'json',
                    data: {id:id},
                    success: function(data){ 
                        if(data.valid){
                            load_services(); 
                        } else {
                            alert(data.msg || 'Failed to delete service.');
                        }
                    }
                });
            }
        }
    </script>
  </body>
</html>
