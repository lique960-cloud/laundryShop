<?php require_once('session.php'); 
// Fetch customer count
require_once('database/Database.php');
$db = new Database();
$custCount = $db->getRow("SELECT COUNT(*) as cnt FROM customers");
$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customers — Laundry Shop</title>
    <meta name="description" content="Laundry Shop Management - Customer Management">
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
            <div class="greeting">Customer Management</div>
            <div class="page-title">Customers</div>
          </div>
        </section>

        <section class="content">
          <!-- Stats -->
          <div class="stat-cards" style="margin-bottom:24px;">
            <div class="stat-card">
              <div class="stat-icon blue"><i class="fa fa-users"></i></div>
              <div class="stat-value"><?= $custCount['cnt'] ?? 0; ?></div>
              <div class="stat-label">Registered Customers</div>
            </div>
          </div>

          <!-- Customer List -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-address-book" style="margin-right:8px; color:#818cf8;"></i>Customer Directory</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="action-bar">
                <button type="button" class="btn btn-primary btn-sm" onclick="loadCustomers()">
                  <i class="fa fa-refresh" style="margin-right:5px;"></i> Refresh
                </button>
              </div>
              <div id="table-customers"></div>
            </div>
            <div class="box-footer">
              <span style="font-size:12.5px; color:#64748b;">
                <i class="fa fa-info-circle" style="margin-right:4px;"></i>
                Customer addresses are used for on-demand pickup & delivery scheduling.
              </span>
            </div>
          </div>

        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 3.0
        </div>
        <strong>Copyright &copy; 2026 <a href="#">Laundry Shop</a>.</strong> All rights reserved.
      </footer>

      <div class="control-sidebar-bg"></div>
    </div>

    <?php include_once('modal/change_password.php'); ?>
    <?php include_once('modal/customer.php'); ?>
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('script.php'); ?>

    <script>
    // Load customers table
    function loadCustomers(){
      $.ajax({
        url: 'data/all_customers.php',
        type: 'post',
        success: function(data){
          $('#table-customers').html(data);
        },
        error: function(){
          $('#table-customers').html('<div style="padding:20px; color:#ef4444;">Failed to load customers.</div>');
        }
      });
    }
    loadCustomers();

    // View customer details
    $(document).on('click', '.view-customer', function(){
      var btn = $(this);
      $('#detail-name').text(btn.data('name'));
      $('#detail-email').text(btn.data('email'));
      $('#detail-mobile').text(btn.data('mobile'));
      $('#detail-address').text(btn.data('address') || 'No address provided');
      $('#detail-date').text('Member since ' + btn.data('date'));
      $('#modal-customer-detail').modal('show');
    });

    // Open delete confirmation
    $(document).on('click', '.delete-customer', function(){
      var btn = $(this);
      $('#delete-customer-id').val(btn.data('id'));
      $('#delete-customer-name').text(btn.data('name'));
      $('#modal-customer-delete').modal('show');
    });

    // Confirm delete
    $(document).on('click', '#confirm-delete-customer', function(){
      var id = $('#delete-customer-id').val();
      var btn = $(this);
      btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
      
      $.ajax({
        url: 'data/delete_customer.php',
        type: 'post',
        dataType: 'json',
        data: { id: id },
        success: function(data){
          $('#modal-customer-delete').modal('hide');
          btn.prop('disabled', false).html('<i class="fa fa-trash" style="margin-right:5px;"></i>Delete');
          if(data.valid){
            loadCustomers();
            $('#modal-msg').find('#msg-body').text(data.msg);
            $('#modal-msg').modal('show');
          } else {
            alert(data.msg || 'Delete failed.');
          }
        },
        error: function(){
          btn.prop('disabled', false).html('<i class="fa fa-trash" style="margin-right:5px;"></i>Delete');
          alert('Connection error.');
        }
      });
    });
    </script>
  </body>
</html>
