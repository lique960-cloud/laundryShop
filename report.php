<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sales Report — Laundry Shop</title>
    <meta name="description" content="Laundry Shop Management - Sales Reports">
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
            <div class="greeting">Financial Overview</div>
            <div class="page-title">Sales Report</div>
          </div>
        </section>

        <section class="content">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-bar-chart" style="margin-right:8px; color:#818cf8;"></i>Daily Sales</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="action-bar">
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                  <label style="color:#94a3b8; font-size:13px; font-weight:600; margin:0;">Select Date:</label>
                  <input id="dailySale" type="date" class="btn btn-default btn-sm" style="min-width:160px;">
                </div>
                <div id="reportActions" style="margin-left:auto; display:flex; gap:8px;">
                  <button id="delSelectedSales" type="button" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash" style="margin-right:5px;"></i> Delete
                  </button>
                  <button id="print-button" type="button" class="btn btn-success btn-sm">
                    <i class="fa fa-print" style="margin-right:5px;"></i> Print Report
                  </button>
                </div>
              </div>
              <div id="table-sales"></div>
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
        <strong>Copyright &copy; 2026 <a href="#">Laundry Shop</a>.</strong> All rights reserved.
      </footer>

      <div class="control-sidebar-bg"></div>
    </div>

    <?php include_once('modal/change_password.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('script.php'); ?>

  </body>
</html>
