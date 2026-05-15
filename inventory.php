<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inventory Management — HypeLaundry</title>
    <meta name="description" content="HypeLaundry Inventory Management - Monitor and manage supplies">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link href="assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-theme.css">
    <style>
      .stock-low { color: #f87171; font-weight: bold; }
      .stock-ok { color: #34d399; }
      .badge-category { background-color: #818cf8; }
      .badge-brand { background-color: #6366f1; }
    </style>
  </head>
  <body class="hold-transition skin-blue sidebar-mini modern-theme">
    <div class="wrapper">

      <header class="main-header">
        <a href="home.php" class="logo">
          <span class="logo-mini"><b>H</b>L</span>
          <span class="logo-lg">🧺 <b>Hype</b>Laundry</span>
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
            <div class="greeting">Inventory Control</div>
            <div class="page-title">Stock Management</div>
          </div>
        </section>

        <section class="content">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-archive" style="margin-right:8px; color:#818cf8;"></i>Manage Supplies</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="action-bar">
                <button id="newItem" type="button" class="btn btn-success btn-sm">
                  <i class="fa fa-plus" style="margin-right:5px;"></i> New Stock
                </button>
                <button id="delItem" type="button" class="btn btn-danger btn-sm">
                  <i class="fa fa-trash" style="margin-right:5px;"></i> Delete
                </button>
              </div>
              <div id="table-inventory"></div>
            </div>
            <div class="box-footer">
              <!-- Footer -->
            </div>
          </div>

        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 3.0
        </div>
        <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> All rights reserved.
      </footer>

      <div class="control-sidebar-bg"></div>
    </div>

    <?php include_once('modal/change_password.php'); ?>
    <?php include_once('modal/inventory.php'); ?>
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('script.php'); ?>
    <script>
        // Inventory specific logic will be here or in admin.js
        $(document).ready(function() {
            all_inventory();
        });

        function all_inventory(){
            $.ajax({
                url: 'data/all_inventory.php',
                type: 'post',
                success: function (data) {
                    $('#table-inventory').html(data);
                    $('#myTable-inventory').DataTable();
                },
                error:function(){
                    alert('Error loading inventory');
                }
            });
        }

        $('#newItem').click(function(event) {
            $('#inv-type').val('insert');
            $('#form-inventory')[0].reset();
            $('#modal-inventory').find('.modal-title').text('New Inventory Item');
            $('#modal-inventory').modal('show');
        });

        $(document).on('submit', '#form-inventory', function(event) {
            event.preventDefault();
            var type = $('#inv-type').val();
            var url = type == 'insert' ? 'data/insert_inventory.php' : 'data/edit_inventory.php';
            
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (data) {
                    if(data.valid){
                        $('#modal-inventory').modal('hide');
                        all_inventory();
                        $('#modal-msg').find('#msg-body').text(data.msg);
                        $('#modal-msg').modal('show');
                    } else {
                        alert(data.msg);
                    }
                }
            });
        });

        function editInventory(id){
            $.ajax({
                url: 'data/get_inventory.php',
                type: 'post',
                dataType: 'json',
                data: { id: id },
                success: function (data) {
                    $('#inv-type').val('edit');
                    $('#inv-id').val(data.id);
                    $('#item_name').val(data.item_name);
                    $('#category_id').val(data.category_id);
                    $('#brand_id').val(data.brand_id);
                    $('#quantity').val(data.quantity);
                    $('#unit').val(data.unit);
                    $('#price').val(data.price);
                    $('#low_stock_threshold').val(data.low_stock_threshold);
                    $('#modal-inventory').find('.modal-title').text('Edit Inventory Item');
                    $('#modal-inventory').modal('show');
                }
            });
        }

        $('#delItem').click(function(event) {
            var selected = $('#table-inventory input[type=checkbox]:checked').length > 0;
            if(!selected){
                alert('Please select items to delete');
            } else {
                $('#confirm-type').val('delete-inventory');
                $('#modal-confirm').modal('show');
            }
        });
    </script>

  </body>
</html>
