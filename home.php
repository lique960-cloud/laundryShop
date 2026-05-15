<?php require_once('session.php'); 
// Fetch stats for dashboard
require_once('database/Database.php');
$db = new Database();
$pendingCount = $db->getRow("SELECT COUNT(*) as cnt FROM laundry WHERE laun_claimed = 0");
$totalIncome = $db->getRow("SELECT COALESCE(SUM(sale_amount),0) as total FROM sales");
$totalCustomers = $db->getRow("SELECT COUNT(*) as cnt FROM customers");
$typeCount = $db->getRow("SELECT COUNT(*) as cnt FROM laundry_type");

// Inventory Stats
$totalInvItems = $db->getRow("SELECT COUNT(*) as cnt FROM inventory");
$lowStockCount = $db->getRow("SELECT COUNT(*) as cnt FROM inventory WHERE quantity <= low_stock_threshold");
$totalInvValue = $db->getRow("SELECT COALESCE(SUM(quantity * price),0) as total FROM inventory");
$lowStockItems = $db->getRows("SELECT item_name, quantity, unit, low_stock_threshold FROM inventory WHERE quantity <= low_stock_threshold LIMIT 5");

$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard — Laundry Shop</title>
    <meta name="description" content="Laundry Shop Management System Dashboard">
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
            <div class="greeting">Good <?= date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : 'Evening'); ?>, Administrator</div>
            <div class="page-title">Dashboard</div>
          </div>
        </section>

        <section class="content">
          <!-- Stats Cards Row 1 -->
          <div class="stat-cards">
            <a href="home.php" class="stat-card">
              <div class="stat-icon blue"><i class="fa fa-shopping-basket"></i></div>
              <div class="stat-value"><?= $pendingCount['cnt'] ?? 0; ?></div>
              <div class="stat-label">Pending Laundry</div>
            </a>
            <a href="report.php" class="stat-card">
              <div class="stat-icon green"><i class="fa fa-money"></i></div>
              <div class="stat-value">₱<?= number_format($totalIncome['total'] ?? 0, 2); ?></div>
              <div class="stat-label">Total Income</div>
            </a>
            <a href="customers.php" class="stat-card">
              <div class="stat-icon yellow"><i class="fa fa-users"></i></div>
              <div class="stat-value"><?= $totalCustomers['cnt'] ?? 0; ?></div>
              <div class="stat-label">Total Customers</div>
            </a>
            <a href="laundrytype.php" class="stat-card">
              <div class="stat-icon cyan"><i class="fa fa-th-list"></i></div>
              <div class="stat-value"><?= $typeCount['cnt'] ?? 0; ?></div>
              <div class="stat-label">Laundry Types</div>
            </a>
          </div>

          <!-- Stats Cards Row 2 (Inventory) -->
          <div class="stat-cards" style="margin-top: 20px;">
            <a href="inventory.php" class="stat-card">
              <div class="stat-icon purple" style="background: rgba(167, 139, 250, 0.1); color: #a78bfa;"><i class="fa fa-archive"></i></div>
              <div class="stat-value"><?= $totalInvItems['cnt'] ?? 0; ?></div>
              <div class="stat-label">Inventory Items</div>
            </a>
            <a href="inventory.php" class="stat-card">
              <div class="stat-icon red" style="background: rgba(248, 113, 113, 0.1); color: #f87171;"><i class="fa fa-warning"></i></div>
              <div class="stat-value"><?= $lowStockCount['cnt'] ?? 0; ?></div>
              <div class="stat-label">Low Stock Alerts</div>
            </a>
            <a href="inventory.php" class="stat-card">
              <div class="stat-icon orange" style="background: rgba(251, 146, 60, 0.1); color: #fb923c;"><i class="fa fa-database"></i></div>
              <div class="stat-value">₱<?= number_format($totalInvValue['total'] ?? 0, 2); ?></div>
              <div class="stat-label">Stock Value</div>
            </a>
          </div>

          <?php if($lowStockCount['cnt'] > 0): ?>
          <!-- Low Stock Alerts -->
          <div class="box box-danger" style="margin-top: 20px;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color: #f87171;"><i class="fa fa-exclamation-triangle" style="margin-right:8px;"></i>Critical Low Stock Alerts</h3>
            </div>
            <div class="box-body">
              <table class="table table-condensed">
                <thead>
                  <tr>
                    <th>Item Name</th>
                    <th>Current Stock</th>
                    <th>Threshold</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($lowStockItems as $item): ?>
                  <tr>
                    <td><?= $item['item_name']; ?></td>
                    <td><span class="text-danger" style="font-weight:bold;"><?= number_format($item['quantity'], 2); ?> <?= $item['unit']; ?></span></td>
                    <td><?= number_format($item['low_stock_threshold'], 2); ?> <?= $item['unit']; ?></td>
                    <td><a href="inventory.php" class="btn btn-xs btn-default">Stock In</a></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php endif; ?>

          <!-- Laundry Orders -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-inbox" style="margin-right:8px; color:#818cf8;"></i>Active Laundry Orders</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="action-bar">
                <button id="newLaun" type="button" class="btn btn-success btn-sm"> 
                  <i class="fa fa-plus" style="margin-right:5px;"></i> New Laundry
                </button>
                <button id="claim" type="button" class="btn btn-primary btn-sm">
                  <i class="fa fa-check" style="margin-right:5px;"></i> Claim & Pay
                </button>
                <button id="delLaun" type="button" class="btn btn-danger btn-sm">
                  <i class="fa fa-trash" style="margin-right:5px;"></i> Delete
                </button>
              </div>
              <div id="table-laundry"></div>
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
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('modal/laundry.php'); ?>
    <?php include_once('script.php'); ?>
  </body>
</html>
