<?php require_once('session.php'); 
$userRole = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : 'admin';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inventory Management — HypeLaundry</title>
    <meta name="description" content="HypeLaundry Sales & Inventory - Stock Management">
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
      .stock-out { color: #ef4444; font-weight: bold; }
      .badge-category { background-color: #818cf8; }
      .badge-brand { background-color: #6366f1; }

      /* Fix DataTables elements for dark theme */
      .dataTables_wrapper .dataTables_filter input {
        background-color: #1a2332 !important;
        color: #f1f5f9 !important;
        border: 1px solid rgba(148, 163, 184, 0.2) !important;
        padding: 6px 12px !important;
        border-radius: 4px !important;
      }
      .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6366f1 !important;
        outline: none !important;
      }
      
      /* Completely redesign pagination to be flat and clean */
      .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        margin-top: 15px;
      }
      
      .pagination {
        display: flex !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        gap: 4px;
      }
      
      .dataTables_wrapper .dataTables_paginate .paginate_button:not(li),
      .pagination > li > a, 
      .pagination > li > span {
        background: #1a2332 !important;
        color: #94a3b8 !important;
        border: 1px solid rgba(148, 163, 184, 0.1) !important;
        padding: 6px 12px !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        text-decoration: none !important;
        font-size: 13px;
      }
      
      .dataTables_wrapper .dataTables_paginate .paginate_button:not(li):hover,
      .pagination > li > a:hover,
      .pagination > li > span:hover {
        background: rgba(99, 102, 241, 0.1) !important;
        color: #818cf8 !important;
        border-color: rgba(99, 102, 241, 0.3) !important;
      }
      
      .dataTables_wrapper .dataTables_paginate .paginate_button.current,
      .pagination > .active > a, 
      .pagination > .active > span {
        background: #6366f1 !important;
        color: #fff !important;
        border-color: #6366f1 !important;
        font-weight: 600;
      }
      
      .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
      .pagination > .disabled > a, 
      .pagination > .disabled > span {
        color: #475569 !important;
        cursor: default !important;
        background: transparent !important;
        border-color: transparent !important;
        opacity: 0.5 !important;
      }
      
      .pagination > li {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      
      /* Fix select dropdown */
      .dataTables_wrapper .dataTables_length select {
        background-color: #1a2332 !important;
        color: #f1f5f9 !important;
        border: 1px solid rgba(148, 163, 184, 0.2) !important;
        padding: 5px !important;
        border-radius: 4px !important;
      }
    </style>
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
            <div class="greeting">Inventory Control</div>
            <div class="page-title">Stock Management</div>
          </div>
        </section>

        <section class="content">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-archive" style="margin-right:8px; color:#818cf8;"></i>Product Inventory</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <?php if($userRole == 'admin'): ?>
              <div class="action-bar">
                <button id="newItem" type="button" class="btn btn-success btn-sm">
                  <i class="fa fa-plus" style="margin-right:5px;"></i> Add Product
                </button>
                <button id="delItem" type="button" class="btn btn-danger btn-sm">
                  <i class="fa fa-trash" style="margin-right:5px;"></i> Delete
                </button>
              </div>
              <?php endif; ?>
              <div id="table-inventory"></div>
            </div>
            <div class="box-footer">
            </div>
          </div>

        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 4.0
        </div>
        <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> Sales & Inventory Management System.
      </footer>

      <div class="control-sidebar-bg"></div>
    </div>

    <?php include_once('modal/change_password.php'); ?>
    <?php include_once('modal/inventory.php'); ?>
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('script.php'); ?>
    <script>
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
            $('#modal-inventory').find('.modal-title').text('Add New Product');
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
                    $('#modal-inventory').find('.modal-title').text('Edit Product');
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
