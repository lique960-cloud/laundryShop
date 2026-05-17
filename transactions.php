<?php require_once('session.php'); 
$userRole = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : 'admin';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Transactions — HypeLaundry</title>
    <meta name="description" content="HypeLaundry Sales & Inventory - Transaction History">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link href="assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-theme.css">
    <style>
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
      .dataTables_wrapper .dataTables_paginate .paginate_button,
      .pagination > li > a, 
      .pagination > li > span {
        background: transparent !important;
        color: #94a3b8 !important;
        border: none !important;
        padding: 6px 14px !important;
        margin: 0 2px !important;
        transition: all 0.3s ease !important;
        cursor: pointer !important;
        border-radius: 4px !important;
      }
      
      .dataTables_wrapper .dataTables_paginate .paginate_button:hover,
      .pagination > li > a:hover,
      .pagination > li > span:hover {
        background: rgba(99, 102, 241, 0.1) !important;
        color: #818cf8 !important;
      }
      
      .dataTables_wrapper .dataTables_paginate .paginate_button.current,
      .pagination > .active > a, 
      .pagination > .active > span {
        background: #6366f1 !important;
        color: #fff !important;
      }
      
      .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
      .pagination > .disabled > a, 
      .pagination > .disabled > span {
        color: #475569 !important;
        cursor: default !important;
        background: transparent !important;
        opacity: 0.5 !important;
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
            <div class="greeting">Transaction Records</div>
            <div class="page-title">Transactions</div>
          </div>
        </section>

        <section class="content">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list-alt" style="margin-right:8px; color:#818cf8;"></i>All Transactions</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="action-bar">
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                  <label style="color:#94a3b8; font-size:13px; font-weight:600; margin:0;">Filter Date:</label>
                  <input id="txn-date" type="date" class="btn btn-default btn-sm" style="min-width:160px;">
                  <button id="txn-show-all" class="btn btn-default btn-sm"><i class="fa fa-list" style="margin-right:4px;"></i>Show All</button>
                </div>
                <?php if($userRole == 'admin'): ?>
                <div style="margin-left:auto; display:flex; gap:8px;">
                  <button id="delSelectedTxns" type="button" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash" style="margin-right:5px;"></i> Delete
                  </button>
                </div>
                <?php endif; ?>
              </div>
              <div id="table-transactions"></div>
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
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('script.php'); ?>

    <script>
    $(document).ready(function() {
      loadTransactions();

      $(document).on('change', '#select-all-txn', function(){
          $('.select-txn').prop('checked', $(this).prop('checked'));
      });
      $(document).on('change', '.select-txn', function(){
          var all = $('.select-txn').length;
          var checked = $('.select-txn:checked').length;
          $('#select-all-txn').prop('checked', all === checked);
      });
    });

    function loadTransactions(date) {
      $.ajax({
        url: 'data/all_transactions.php',
        type: 'post',
        data: { date: date || '' },
        success: function(data) {
          $('#table-transactions').html(data);
          $('#myTable-txn').DataTable({
            columnDefs: [{ orderable: false, targets: [0, 7] }],
            order: [[5, 'desc']]
          });
        },
        error: function() { 
          alert('Error loading transactions'); 
        }
      });
    }

    $('#txn-date').change(function() {
      loadTransactions($(this).val());
    });

    $('#txn-show-all').click(function() {
      $('#txn-date').val('');
      loadTransactions();
    });

    $(document).on('click', '.print-txn', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      window.open('data/print_receipt.php?id=' + id, '_blank', 'width=400,height=600');
    });

    $(document).on('click', '.delete-txn', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      $('#confirm-type').val('delete-transaction');
      $('#confirm-id').val(id);
      $('#modal-confirm').modal('show');
    });

    $('#delSelectedTxns').click(function() {
      var checked = $('#table-transactions input.select-txn:checked').length;
      if(checked === 0) {
        alert('Please select transaction(s) to delete.');
        return;
      }
      $('#confirm-type').val('delete-transaction-bulk');
      $('#modal-confirm').modal('show');
    });

    // View transaction details
    $(document).on('click', '.view-txn', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      $.ajax({
        url: 'data/get_transaction.php',
        type: 'post',
        dataType: 'json',
        data: { id: id },
        success: function(data) {
          if(data.sale) {
            var html = '<div style="padding:10px 0;">';
            html += '<div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span style="color:#94a3b8;">Reference:</span><strong style="font-family:monospace;color:#818cf8;">' + data.sale.sale_reference + '</strong></div>';
            html += '<div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span style="color:#94a3b8;">Customer:</span><strong>' + data.sale.sale_customer_name + '</strong></div>';
            html += '<div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span style="color:#94a3b8;">Payment:</span><strong>' + data.sale.sale_payment_method + '</strong></div>';
            html += '<div style="display:flex;justify-content:space-between;margin-bottom:12px;"><span style="color:#94a3b8;">Date:</span><strong>' + data.sale.sale_date + '</strong></div>';
            html += '<hr style="border-color:rgba(148,163,184,0.1);margin:10px 0;">';
            html += '<table style="width:100%;font-size:13px;"><thead><tr style="color:#94a3b8;font-size:11px;"><th>Item</th><th style="text-align:center;">Qty</th><th style="text-align:right;">Subtotal</th></tr></thead><tbody>';
            data.items.forEach(function(item) {
              html += '<tr><td style="padding:4px 0;">' + item.item_name + '</td><td style="text-align:center;padding:4px 0;">' + item.quantity + '</td><td style="text-align:right;padding:4px 0;color:#10b981;">₱' + parseFloat(item.subtotal).toFixed(2) + '</td></tr>';
            });
            html += '</tbody></table>';
            html += '<hr style="border-color:rgba(148,163,184,0.1);margin:10px 0;">';
            html += '<div style="display:flex;justify-content:space-between;font-size:16px;font-weight:700;"><span>Total:</span><span style="color:#10b981;">₱' + parseFloat(data.sale.sale_total).toFixed(2) + '</span></div>';
            html += '</div>';
            
            $('#modal-msg').find('.modal-title').html('<i class="fa fa-receipt" style="margin-right:8px;color:#0ea5e9;"></i>Transaction Details');
            $('#modal-msg').find('#msg-body').html(html);
            $('#modal-msg').modal('show');
          }
        }
      });
    });
    </script>
  </body>
</html>
